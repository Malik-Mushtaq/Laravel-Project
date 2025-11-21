@extends('layouts.app')

@section('title', 'Login')

@section('content')

<style>
    body {
        background: #f8f9fa;
    }
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        width: 100%;
        max-width: 420px;
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
    }
    .login-card .card-body {
        padding: 2rem;
    }
    .form-control {
        border-radius: 10px;
    }
    .btn-primary {
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        padding: 10px;
    }
    .brand {
        font-size: 1.8rem;
        font-weight: bold;
        color: #0d6efd;
        text-align: center;
        margin-bottom: 1rem;
    }
    .text-small {
        font-size: 0.9rem;
    }
    .alert {
        border-radius: 10px;
        padding: 8px 12px;
    }
</style>

<div class="login-container">
    <div class="card login-card">
        <div class="card-body">
            <div class="brand">🏠 Rentify</div>
            <h4 class="text-center mb-4 fw-semibold">Login to Your Account</h4>

            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Login</button>

                <div class="text-center mt-3 text-small">
                    Don’t have an account? <a href="/register">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    @if(session()->has('setLocalStorage'))
        const authData = @json(session('setLocalStorage'));
        console.log('Auth data from session:', authData); // Debug: should show username, role, email

        if(authData.username) localStorage.setItem('username', authData.username);
        if(authData.role) localStorage.setItem('role', authData.role);
    
    @endif

    // Clear localStorage if user logs out
    @if(session()->has('clearLocalStorage'))
        localStorage.removeItem('username');
        localStorage.removeItem('role');
       
    @endif
});
</script>

@endsection
