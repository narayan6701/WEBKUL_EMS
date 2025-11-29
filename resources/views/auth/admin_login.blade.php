@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="auth-page">
  <div class="auth-card" role="form" aria-labelledby="login-title">
    <h1 id="login-title">Admin Login</h1>
    <p>Secure sign-in for authorized administrators only.</p>
     @error('email')
        <div class="alert alert-danger" style="padding: 0.75rem 1rem; margin-bottom: 1rem; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px;">
            {{ $message }}
        </div>
    @enderror

    <form action="{{ route('admin_login') }}" method="post">
        @csrf
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter your password">

        <button type="submit">Sign in</button>

        <div class="auth-actions" aria-hidden="false">
            <a href="/user_login">Login as Employee</a>
        </div>
    </form>
  </div>
</div>
@endsection