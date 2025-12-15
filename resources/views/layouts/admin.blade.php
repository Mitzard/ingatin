<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Ingat.in')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Font: Raleway SemiBold -->
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo-ingatin.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/logo-ingatin.png') }}" type="image/x-icon">
    <style>
        /* CSS WAJIB DITAMBAHKAN ke layouts/app.blade.php atau file CSS eksternal */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
            /* Di Mobile, wrapper tidak perlu padding atas karena navbar-nya di luar wrapper */
        }

        /* 2. SIDEBAR STATIS (Desktop) */
        .sidebar {
            width: 250px;
            min-width: 250px;
            background-color: #343A40;
            color: #fff;
            padding: 15px;
            position: fixed;
            /* Penting agar sidebar tetap di tempat saat konten di-scroll */
            top: 0;
            left: 0;
            height: 100vh;
            transition: transform 0.3s ease, width 0.3s ease;
            z-index: 1030;
            /* Di bawah navbar fixed utama jika ada */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
        }

        /* 3. KONTEN UTAMA (Desktop) */
        .main-content-wrapper {
            margin-left: 260px;
            /* Memberi ruang untuk sidebar di Desktop */
            flex-grow: 1;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* 4. NAVBAR HEADER (Hanya Navbar Header Admin, bukan Navbar Fixed Top) */
        .admin-header {
            background-color: #fff;
            padding: 10px 20px;
            width: 100%;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.05);
        }

        /* 5. TAMPILAN MOBILE (< 992px) */
        @media (max-width: 991px) {
            .sidebar {
                /* Di mobile, sidebar disembunyikan secara default (Offcanvas) */
                transform: translateX(-250px);
                z-index: 1040;
                /* Harus di atas konten dan navbar */
            }

            .main-content-wrapper {
                margin-left: 0;
                /* Hapus margin di mobile */
            }

            /* Tambahkan kelas khusus untuk memunculkan sidebar di mobile */
            .sidebar.show {
                transform: translateX(0);
            }

            /* Tombol Hamburger di mobile harus muncul */
            .btn-mobile-toggle {
                display: block !important;
            }
        }
    </style>
</head>

<body>

    {{-- Include Navbar --}}
    @include('layouts.sidebar')

    {{-- Konten halaman --}}
    <div class="main-content-wrapper">

        {{-- 3. ADMIN HEADER BAR --}}
        <div class="admin-header d-flex justify-content-between align-items-center">
        </div>

        {{-- 4. KONTEN (Area Kalender) --}}
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sideMenu');
            const toggleBtn = document.getElementById('mobileSidebarToggle'); // Tombol hamburger di Admin Header

            // Logic untuk toggle di mobile
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            // Logic untuk menutup sidebar saat klik link di mobile (opsional)
            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('show');
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
