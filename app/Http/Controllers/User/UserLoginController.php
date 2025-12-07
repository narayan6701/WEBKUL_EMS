<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'cred' => ['required'], // Input name in form should be 'cred'
            'password' => ['required'],
        ]);

        $input = $request->input('cred');
        $remember = $request->filled('remember');

        $emailCreds = ['email' => $input, 'password' => $request->password];
        $phoneCreds = ['phone' => $input, 'password' => $request->password];


        if (Auth::attempt($emailCreds, $remember) || Auth::attempt($phoneCreds, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/user_profile');
        }

        return back()->withErrors([
            'cred' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        // 1. If Admin is logged in, send to Admin Dashboard
        if (Auth::guard('admin')->check()) {
            return redirect('/admin_dashboard');
        }

        // 2. If User is logged in, send to User Profile
        if (Auth::check()) {
            return redirect('/user_profile');
        }

        // 3. Otherwise, show the login form
        return view('auth.user_login');
    }
}
