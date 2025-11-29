<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function __contruct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        // 1. If Admin is logged in, send to Admin Dashboard
        if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            return redirect()->route('admin_dashboard');
        }

        // 2. If User is logged in, send to User Profile
        if (\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('user_profile');
        }

        return view('auth.admin_login'); // Ensure you have this view created
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::guard('admin')->attempt($credentials, $request->filled('remember'))){
            $request->session()->regenerate();
            return redirect()->intended('/admin_dashboard');
        }

        return back()->withErrors([
            'email'=>'The provided details do not match our records.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/user_login');
    }
}