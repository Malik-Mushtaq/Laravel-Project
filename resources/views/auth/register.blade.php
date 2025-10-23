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

            <div id="errorMsg" class="alert alert-danger text-center d-none"></div>
            <div id="successMsg" class="alert alert-success text-center d-none"></div>

            <form id="registerForm">
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your full name" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Re-enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Sign Up</button>

                <div class="text-center mt-3 text-small">
                    Already have an account? <a href="/login">Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('registerForm');
    const errorMsg = document.getElementById('errorMsg');
    const successMsg = document.getElementById('successMsg');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const password_confirmation = document.getElementById('password_confirmation').value.trim();

        if (password !== password_confirmation) {
            showError("Passwords do not match.");
            return;
        }

        const users = JSON.parse(localStorage.getItem('users')) || [];

        if (users.some(user => user.email === email)) {
            showError("Email is already registered. Please login.");
            return;
        }

        const newUser = { name, email, password, role: "User" };
        users.push(newUser);
        localStorage.setItem('users', JSON.stringify(users));

        localStorage.setItem("user", "authenticated");
        localStorage.setItem("username", name);

        showSuccess("Registration successful! Redirecting...");

        setTimeout(() => {
            window.location.href = "/properties";
        }, 1000);
    });

    function showError(message) {
        errorMsg.textContent = message;
        errorMsg.classList.remove("d-none");
        successMsg.classList.add("d-none");
    }

    function showSuccess(message) {
        successMsg.textContent = message;
        successMsg.classList.remove("d-none");
        errorMsg.classList.add("d-none");
    }
});
</script>
@endsection
