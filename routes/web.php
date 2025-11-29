<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\User\UserRegisterController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use Illuminate\Support\Facades\Auth;

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
// IMPORTANT: Name this route 'login' so Laravel's default auth middleware works
Route::get('/user_login', [UserLoginController::class, 'showLoginForm'])->name('login');
Route::post('/user_login', [UserLoginController::class, 'login'])->name('user_login_attempt');
Route::post('/user_logout', [UserLoginController::class, 'logout'])->name('user_logout');

Route::get('/user_register', function () {
    // Manual Guest Check: If logged in, don't show register page
    if(Auth::check()) return redirect()->route('user_profile');
    if(Auth::guard('admin')->check()) return redirect()->route('admin_dashboard');
    return view('auth.user_register');
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
Route::get('/admin_login', [AdminLoginController::class, 'showLoginForm'])->name('admin_login');
Route::post('/admin_login', [AdminLoginController::class, 'login'])->name('admin_login_attempt');
Route::post('/admin_logout', [AdminLoginController::class, 'logout'])->name('admin_logout');

// Protected Admin Routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin_dashboard', [AdminDashboardController::class, 'index'])->name('admin_dashboard');
    Route::get('/admin_view_details/{employee}', [AdminDashboardController::class, 'show'])->name('admin_view_details');
});