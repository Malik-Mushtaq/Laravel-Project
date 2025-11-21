@extends('layouts.app')

@section('title', 'Register')

@section('content')
<style>
    body {
        background: #f8f9fa;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .register-card {
        margin:20px;
        width: 100%;
        max-width: 450px;
        border: none;
        border-radius: 20px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
    }

    .register-card .card-body {
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

<div class="register-container">
    <div class="card register-card">
        <div class="card-body">
            <div class="brand">🏠 Rentify</div>
            <h4 class="text-center mb-4 fw-semibold">Create an Account</h4>

            @if ($errors->any())
                <div class="alert alert-danger text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.process') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter your full name" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Re-enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Sign Up</button>

                <div class="text-center mt-3 text-small">
                    Already have an account? <a href="/login">Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
