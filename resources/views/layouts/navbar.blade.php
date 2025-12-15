<nav class="navbar navbar-expand-lg fixed-top shadow-sm" style="background-color: #ffffff; border: 3px solid #952638;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-ingatin.png') }}" alt="Logo SI Kegiatan RT" style="height: 35px;"
                class="me-2">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel" style="color: #952638;">Menu INGAT.IN!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">

                    {{-- Beranda (PUBLIC) --}}
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 text-black {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            Beranda
                            <span
                                class="active-line {{ request()->routeIs('home') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li>

                    {{-- Kalender Kegiatan (PROTECTED) --}}
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 text-black {{ request()->routeIs('warga.calendar') ? 'active' : '' }}"
                            href="{{ route('warga.calendar') }}">
                            Kalender Kegiatan
                            <span
                                class="active-line {{ request()->routeIs('warga.calendar') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li>

                    {{-- Daftar Kegiatan (PROTECTED) --}}
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 text-black {{ request()->routeIs('daftar') ? 'active' : '' }}"
                            href="{{ route('daftar') }}">
                            Daftar Kegiatan
                            <span
                                class="active-line {{ request()->routeIs('daftar') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li>

                    {{-- Arsip Kegiatan (PROTECTED) --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link mx-lg-2 {{ request()->routeIs('archive') ? 'active' : '' }}"
                            href="{{ route('archive') }}">
                            Arsip Kegiatan
                            <span
                                class="active-line {{ request()->routeIs('archive') ? 'd-none d-lg-block' : 'd-none' }}"></span>
                        </a>
                    </li> --}}

                    {{-- About (PUBLIC) --}}
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

        {{-- TAMPILAN GUEST (BELUM LOGIN) --}}
        @guest
            {{-- Menggunakan class CSS yang Anda definisikan sebelumnya untuk tombol Login --}}
            <a href="{{ route('login') }}" class="login-button">
                Masuk
            </a>
        @endguest

        {{-- TAMPILAN AUTH (SUDAH LOGIN) --}}
        @auth
            {{-- Menggunakan helper function isPengurus() untuk checking role --}}
            @if (!Auth::user()->isPengurus())
                {{-- Dropdown Profile Warga --}}
                <div class="dropdown">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle p-0"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- Menggunakan accessor profile_photo_url yang menangani placeholder --}}
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Photo Profile" width="40" height="40"
                            class="rounded-circle border border-2 border-danger">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 200px;">
                        <li class="px-3 py-2">
                            <p class="small fw-semibold mb-0">{{ Auth::user()->nama_lengkap }}</p>
                            <p class="small mb-0">{{ Auth::user()->email }}</p>
                            <p class="small mb-0">{{ Auth::user()->nomor_telepon }}</p>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li><a class="dropdown-item fw-semibold" href="{{ route('profile') }}"><i class="bi bi-person-circle me-2"></i>
                                Lihat Profil</a></li>
                        <li>
                        <hr class="dropdown-divider">
                        </li>

                        {{-- Tombol Logout --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                {{-- Tampilan untuk Role PENGURUS --}}
                <a href="{{ route('dashboard') }}" class="btn btn-warning fw-bold">
                    Dashboard Pengurus
                </a>
            @endif
        @endauth

    </div>
</nav>
