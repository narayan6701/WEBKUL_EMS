@extends('layouts.app')

@section('title', 'Employee Login')

@section('content')

<div class="auth-page">
  <div class="auth-card" role="form" aria-labelledby="login-title">
    <h1 id="login-title">Employee Login</h1>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form method="POST" action"/user_login">
        @csrf
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required placeholder="Enter your password">
        </div>

        <button type="submit">Sign in</button>

        <div class="auth-actions" aria-hidden="false">
            {{-- Use route() helper here --}}
            <a href="{{ route('admin_login') }}">Login as Admin</a>
            <a href="{{ route('user_register') }}">Create an Employee Account</a>
        </div>
    </form>
  </div>
</div>

@endsection