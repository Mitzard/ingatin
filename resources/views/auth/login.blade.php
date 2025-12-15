<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" href="{{ asset('images/logo-ingatin.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/logo-ingatin.png') }}" type="image/x-icon">

    <style>
        /* ... (CSS Anda di sini - tidak diubah untuk menjaga desain) ... */
        body {
            background-image: url("{{ asset('images/background-image.jpg') }}"); 
            background-color: rgba(0, 0, 0, 0.5);
            background-blend-mode: darken;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background-color: #3C3C3C;
            color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            padding: 25px;
        }

        @media (min-width: 768px) {
            .card {
                max-width: 450px;
                padding: 30px;
            }
        }

        @media (min-width: 992px) {
            .card {
                max-width: 500px;
                /* laptop */
            }
        }

        .btn-primary {
            width: 100%;
            background-color: #C1203A;
            border: none;
            font-weight: 600;
            padding: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #a81830;
        }

        .logo {
            display: block;
            margin: 0 auto 25px auto;
            width: 60px;
            height: auto;
        }

        label {
            color: #ddd;
            font-size: 0.95rem;
        }

        input.form-control {
            border: 1px solid #495057;
            font-size: 0.95rem;
            padding: 10px;
        }

        input.form-control::placeholder {
            color: #aaa;
        }

        input.form-control:focus {
            box-shadow: none;
            border-color: #C1203A;
        }

        .text-center a {
            color: #ff002b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .text-center a:hover {
            text-decoration: none;
            color: #a81830;
        }

        .alert {
            font-size: 0.9rem;
        }

        /* Tambahan: Gaya untuk indikator validasi */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
        }

        .input-group .form-control {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            border-right: 0;
            /* Hilangkan garis batas kanan */
        }

        .input-group .btn-toggle-password {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-left: 0;
            /* Hilangkan garis batas kiri */
            background-color: white;
            /* Samakan warna background dengan input */
            border-color: #495057;
            /* Samakan warna border dengan input */
            color: #333;
            z-index: 5;
        }

        .input-group .btn-toggle-password:hover {
            background-color: #f8f9fa;
            border-color: #495057;
        }

        /* Fix agar garis border tetap terlihat rapi saat diklik */
        .input-group .form-control:focus {
            z-index: 3;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="card-body">
            <img src="{{ asset('images/logo-ingatin.png') }}" alt="Logo INGAT.IN" class="logo">
            <h4 class="text-center text-danger">Selamat Datang Kembali!</h4>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- 1. FIELD LOGIN (Username/Email/Nomor HP/NIK) --}}
                <div class="mb-3">
                    <label for="login_field" class="form-label">Email / No. HP</label>
                    <input type="text" class="form-control @error('login_field') is-invalid @enderror"
                        id="login_field" name="login_field" placeholder="Masukkan Email atau Nomor HP Anda" required
                        value="{{ old('login_field') }}">
                    @error('login_field')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- 2. FIELD PASSWORD --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group has-validation">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" placeholder="Masukkan Kata Sandi" required>
                        <button class="btn btn-toggle-password btn-light toggle-password" type="button" data-target="password">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Masuk</button>

                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="{{ route('register') }}">Registrasi</a></p>
                </div>

                {{-- TAMPILAN PESAN SUKSES DARI REGISTRASI --}}
                @if (session('success'))
                    <div class="alert alert-success mt-3 text-center" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-slash-fill');
                    icon.classList.add('bi-eye-fill');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-fill');
                    icon.classList.add('bi-eye-slash-fill');
                }
            });
        });
    </script>
</body>

</html>
