@extends('layouts.app')

@section('content')
<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        max-width: 450px;
        width: 100%;
        transform: translateY(0);
        transition: all 0.3s ease;
    }
    
    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .login-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 30px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .login-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .login-header h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }
    
    .login-header p {
        margin: 10px 0 0 0;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    .login-body {
        padding: 40px 30px;
    }
    
    .form-group {
        margin-bottom: 25px;
        position: relative;
    }
    
    .form-control {
        background: rgba(248, 249, 250, 0.8);
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 16px;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .form-control:focus {
        background: white;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        transform: translateY(-2px);
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }
    
    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 15px 30px;
        font-size: 16px;
        font-weight: 600;
        color: white;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    
    .btn-login:hover::before {
        left: 100%;
    }
    
    .btn-login:active {
        transform: translateY(-1px);
    }
    
    .form-check {
        margin: 20px 0;
    }
    
    .form-check-input {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .form-check-label {
        margin-left: 8px;
        color: #6c757d;
        font-weight: 500;
    }
    
    .forgot-password {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        text-align: center;
        display: block;
        margin-top: 20px;
        transition: color 0.3s ease;
    }
    
    .forgot-password:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 8px;
        font-size: 14px;
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
        padding: 8px 12px;
        border-radius: 8px;
        border-left: 4px solid #dc3545;
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .login-footer {
        text-align: center;
        padding: 20px;
        background: rgba(248, 249, 250, 0.5);
        color: #6c757d;
        font-size: 14px;
    }
    
    @media (max-width: 576px) {
        .login-container {
            padding: 10px;
        }
        
        .login-header {
            padding: 30px 20px;
        }
        
        .login-header h2 {
            font-size: 1.5rem;
        }
        
        .login-body {
            padding: 30px 20px;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>{{ __('Welcome Back') }}</h2>
            <p>{{ __('Please sign in to your account') }}</p>
        </div>

        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" 
                           type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email" 
                           autofocus
                           placeholder="Enter your email address">

                    @error('email')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" 
                           type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="Enter your password">

                    @error('password')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="remember" 
                           id="remember" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    {{ __('Sign In') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </form>
        </div>

        <div class="login-footer">
            {{ __('© 2024 Your Company Name. All rights reserved.') }}
        </div>
    </div>
</div>
@endsection