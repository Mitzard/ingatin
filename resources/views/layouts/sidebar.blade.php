<div class="sidebar" id="sideMenu">
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <h5 class="offcanvas-title fw-semibold" id="sideMenuLabel" style="color: #DC3545;">
            <img src="{{ asset('images/logo-ingatin.png') }}" alt="Logo" style="width: 30px;" class="me-2">
            INGAT.IN
        </h5>

        {{-- Tombol Tutup Sidebar (Hanya untuk Mobile) --}}
        <button type="button" class="btn-close d-lg-none" data-bs-dismiss="sidebar-mobile" aria-label="Close"></button>
    </div>
    <hr>
    <div class="sidebar-body mt-4">
        <ul class="list-unstyled ps-0">
            {{-- Navigasi Utama --}}
            <li><a href="{{ route('dashboard') }}"
                    class="p-2 d-block text-decoration-none rounded {{ request()->routeIs('dashboard') ? 'bg-danger text-white fw-bold' : 'text-light' }}"><i
                        class="bi bi-house-door me-2"></i> Dashboard</a></li>
            <li><a href="{{ route('kelola.kegiatan') }}"
                    class="p-2 d-block rounded text-decoration-none {{ request()->routeIs('kelola.kegiatan') ? 'bg-danger text-white fw-bold' : 'text-light' }}"><i
                        class="bi bi-calendar-event me-2"></i> Kelola Jadwal</a></li>

            {{-- Logout --}}
            <li class="mb-3"><a href="{{ route('pengaturan') }}"
                    class="p-2 d-block rounded text-decoration-none {{ request()->routeIs('pengaturan') ? 'bg-danger text-white fw-bold' : 'text-light' }}"><i
                        class="bi bi-gear-fill me-2"></i> Ganti Password</a></li>
            <li class="border-top"><a href="#" onclick="document.getElementById('logout-form').submit();"
                    class=" fw-bold text-decoration-none d-block p-2" style="color: #f9284b;"><i
                        class="bi bi-box-arrow-right me-2"></i>
                    Logout</a></li>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </ul>
    </div>
</div>
