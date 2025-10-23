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

            <div id="errorMsg" class="alert alert-danger text-center d-none"></div>
            <div id="successMsg" class="alert alert-success text-center d-none"></div>

            <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
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
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('loginForm');
    const errorMsg = document.getElementById('errorMsg');
    const successMsg = document.getElementById('successMsg');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        // Hardcoded users
        const users = [
            { email: "admin@gmail.com", password: "123456", role: "Admin" },
            { email: "user@gmail.com", password: "123456", role: "User" }
        ];

        const user = users.find(u => u.email === email && u.password === password);

        if (!user) {
            errorMsg.textContent = "Invalid email or password.";
            errorMsg.classList.remove("d-none");
            successMsg.classList.add("d-none");
            return;
        }

        // Store user info in localStorage for later access
        localStorage.setItem("user", "authenticated");
        localStorage.setItem("username", user.role);

        successMsg.textContent = "Login successful! Redirecting...";
        successMsg.classList.remove("d-none");
        errorMsg.classList.add("d-none");

        setTimeout(() => {
            if (user.role === "Admin") {
                window.location.href = "/admin/dashboard";
            } else {
                window.location.href = "/properties";
            }
        }, 1000);
    });
});
</script>
@endsection
