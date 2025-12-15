<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ingat.IN')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" href="{{ asset('images/logo-ingatin.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/logo-ingatin.png') }}" type="image/x-icon">
    @stack('styles')

    <style>
        body {
            /* padding-top: 5rem; */
            background-color: #FFF;
        }

        /* Tambahkan CSS Navbar di sini jika tidak menggunakan asset('css/app.css') */
        .navbar {
            background-color: #FFF1F1;
            height: 80px;
            margin: 20px;
            border-radius: 16px;
            padding: 0.5rem;
        }

        .navbar-brand {
            font-weight: 500;
            color: #009970;
            font-size: 24px;
        }

        .login-button {
            background-color: #009970;
            color: #fff;
            font-size: 14px;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            transition: 0.3s background-color;
            font-weight: 600;
        }

        .login-button:hover {
            background-color: #00b383;
        }

        .navbar-toggler {
            border: none;
            font-size: 1.25rem;
        }

        .navbar-toggler:focus,
        .btn-close:focus {
            box-shadow: none;
            font-size: 1.25rem;
        }

        .nav-link {
            color: #666777;
            font-weight: 500;
            position: relative;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #000;
        }
        
        @media (min-width: 991px) {
            .nav-link::before {
                content: "";
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 0%;
                height: 2px;
                background-color: #88304E;
                visibility: hidden;
                transition: 0.3s ease-in-out;
            }

            .nav-link:hover::before,
            .nav-link.active::before {
                width: 100%;
                visibility: visible;
            }
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <main>
        <div>
            {{-- <div class="container">
            <!-- Menampilkan pesan flash (success, error, warning) -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Silakan periksa kembali input Anda.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}

            @yield('content')
        </div>
    </main>

    @include('layouts.footer')
    <!-- Footer dapat diletakkan di sini -->

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
