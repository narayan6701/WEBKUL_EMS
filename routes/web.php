<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\User\UserRegisterController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Root Route (Manual Check)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if(Auth::guard('admin')->check()){
        return redirect()->route('admin_dashboard');
    }
    if(Auth::check()){
        return redirect()->route('user_profile');
    }
    return redirect('/user_login'); // Redirects to user_login
})->name('home');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
// IMPORTANT: Name this route 'login'
Route::get('/user_login', function () {
    if (Auth::guard('admin')->check()) {
        return redirect('/admin_dashboard');
    }
    if (Auth::check()) {
        return redirect('/user_profile');
    }

    $controller = app()->make(UserLoginController::class);
    $resp = $controller->showLoginForm();

    // Ensure we return a Response instance and attach no-cache headers
    if ($resp instanceof Response) {
        return $resp->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
    }

    return response($resp)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('login');
Route::post('/user_login', [UserLoginController::class, 'login'])->name('user_login_attempt');
Route::post('/user_logout', [UserLoginController::class, 'logout'])->name('user_logout');

Route::get('/user_register', function () {
    // If admin is logged in, keep them on admin dashboard
    if (Auth::guard('admin')->check()) {
        return redirect('/admin_dashboard');
    }

    // If normal user is logged in, keep them on user profile
    if (Auth::check()) {
        return redirect('/user_profile');
    }

    // this prevent showing of register page if some one is logged when using navigation buttons of the browser
    return response()->view('auth.user_register')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('user_register');

Route::post('/user_register', [UserRegisterController::class, 'store'])->name('user_register_store');

// Protected User Routes
Route::middleware('auth')->group(function () {
    Route::get('/user_profile', [UserProfileController::class, 'edit'])->name('user_profile');
    Route::patch('/user_profile', [UserProfileController::class, 'update'])->name('user_profile_update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin_login', function () {
    if (Auth::guard('admin')->check()) {
        return redirect('/admin_dashboard');
    }
    if (Auth::check()) {
        return redirect('/user_profile');
    }

    $controller = app()->make(AdminLoginController::class);
    $resp = $controller->showLoginForm();

    if ($resp instanceof Response) {
        return $resp->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
    }

    return response($resp)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('admin_login');
Route::post('/admin_login', [AdminLoginController::class, 'login'])->name('admin_login_attempt');
Route::post('/admin_logout', [AdminLoginController::class, 'logout'])->name('admin_logout');

// Protected Admin Routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin_dashboard', [AdminDashboardController::class, 'index'])->name('admin_dashboard');
    Route::get('/admin_view_details/{employee}', [AdminDashboardController::class, 'show'])->name('admin_view_details');
});