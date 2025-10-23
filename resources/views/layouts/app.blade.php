<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentify - Find Your Perfect Rental Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }

        .hero {
            background: url('{{ asset('assets/img/hero-bg.jpg') }}') center/cover no-repeat;
            color: #fff;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
        }

        .hero-content {
            position: relative;
            text-align: center;
            z-index: 2;
        }

        .search-box {
            background: #fff;
            border-radius: 30px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .why-choose, .featured { padding: 80px 0; }
        .footer { background: #111; color: #ccc; }

        /* Minimal navbar for login/signup */
        .auth-navbar {
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 1rem 0;
            text-align: center;
        }

        .auth-navbar .navbar-brand {
            font-weight: bold;
            font-size: 1.6rem;
            color: #007bff !important;
            text-decoration: none;
        }
    </style>
</head>

<body>

    @if (request()->is('login') || request()->is('register'))
        <nav class="auth-navbar">
            <a href="/" class="navbar-brand">🏠 Rentify</a>
        </nav>
    @else
        {{-- Full navbar everywhere else --}}
        <x-navbar />
    @endif

    <main class="{{ request()->is('login') || request()->is('register') ? '' : 'mt-5 ' }}">
        @yield('content')
    </main>
    @unless (request()->is('login') || request()->is('register'))
        <x-footer />
    @endunless

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
