<nav class="navbar navbar-expand-lg fixed-top shadow-sm"
    style="background-color: #ffffff; border-bottom: 3px solid #952638;">
    <div class="container-fluid">

        {{-- 1. TOMBOL BURGER (DITARUH PERTAMA AGAR DI KIRI) --}}
        <button class="navbar-toggler me-2 border-0" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- 2. LOGO (DITARUH KEDUA) --}}
        {{-- 'me-auto' digunakan agar elemen setelah ini (Auth buttons) terdorong ke kanan mentok --}}
        <a class="navbar-brand d-flex align-items-center me-auto" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-ingatin.png') }}" alt="Logo" style="height: 35px;" class="me-2">
        </a>

        {{-- 3. MENU OFFCANVAS (MUNCUL DARI KIRI) --}}
        {{-- Perhatikan perubahan: 'offcanvas-start' --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">

            <div class="offcanvas-header">
                {{-- Logo di dalam menu mobile --}}
                <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel" style="color: #952638;">
                    INGAT.IN!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">

                    {{-- Beranda --}}
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 text-black {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            Beranda
                            <span
                                class="active-line {{ request()->routeIs('home') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li>

                    {{-- Kalender --}}
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 text-black {{ request()->routeIs('warga.calendar') ? 'active' : '' }}"
                            href="{{ route('warga.calendar') }}">
                            Kalender Kegiatan
                            <span
                                class="active-line {{ request()->routeIs('warga.calendar') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li>

                    {{-- Daftar --}}
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 text-black {{ request()->routeIs('daftar') ? 'active' : '' }}"
                            href="{{ route('daftar') }}">
                            Daftar Kegiatan
                            <span
                                class="active-line {{ request()->routeIs('daftar') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li>

                    {{-- Tentang Kami --}}
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 text-black {{ request()->routeIs('about') ? 'active' : '' }}"
                            href="{{ route('about') }}">
                            Tentang Kami
                            <span
                                class="active-line {{ request()->routeIs('about') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        {{-- 4. BAGIAN AUTH (LOGIN/PROFILE) --}}
        {{-- Bagian ini tetap di luar offcanvas agar selalu terlihat di pojok kanan --}}

        @auth
            <div class="d-flex align-items-center gap-3">
                @if (Auth::user()->isPengurus())
                    {{-- Tombol Dashboard (Hidden di layar HP kecil banget biar ga penuh, opsional) --}}
                    <a href="{{ route('dashboard') }}"
                        class="btn btn-warning btn-sm fw-bold shadow-sm d-none d-sm-inline-block">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                @endif

                <div class="dropdown">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle p-0"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- FOTO PROFILE --}}
                        <img src="{{ Auth::user()->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama_lengkap) }}"
                            alt="Photo Profile" width="40" height="40"
                            class="rounded-circle border border-2 border-danger shadow-sm" style="object-fit: cover;">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 220px;">
                        <li class="px-3 py-2 bg-light border-bottom">
                            <p class="fw-semibold mb-0 text-truncate">{{ Auth::user()->nama_lengkap }}</p>
                            <p class="small text-muted mb-0 text-truncate">{{ Auth::user()->nomor_telepon }}</p>
                        </li>
                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>
                        <li>
                            <a class="dropdown-item fw-semibold" href="{{ route('profile') }}">
                                <i class="bi bi-person-circle me-2 text-secondary"></i> Lihat Profil
                            </a>
                        </li>

                        @if (Auth::user()->isPengurus())
                            <li>
                                <a class="dropdown-item fw-semibold text-warning" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> Ke Dashboard
                                </a>
                            </li>
                        @endif

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @else
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-success fw-semibold btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-success fw-semibold btn-sm">Daftar</a>
            </div>
        @endauth
    </div>
</nav>