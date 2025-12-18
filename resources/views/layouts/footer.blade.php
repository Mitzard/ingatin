<footer class="mt-auto pt-5 pb-3 position-relative overflow-hidden" style="background-color: #282828; color: #E9ECEF;">
    <div class="container position-relative" style="z-index: 10;">
        <div class="row g-4 g-lg-5">

            {{-- Kolom 1: Logo dan Deskripsi --}}
            {{-- Mobile: Full width & Center | Desktop: 5/12 width & Left Align --}}
            <div class="col-12 col-lg-5 mb-4 mb-lg-0 text-center text-lg-start">

                {{-- Logo Wrapper --}}
                <a href="{{ route('home') }}"
                    class="fs-4 fw-semibold mb-3 d-inline-flex d-lg-flex align-items-center justify-content-center justify-content-lg-start text-decoration-none">
                    <img src="{{ asset('images/logo-ingatin.png') }}" alt="Logo Ingat.in" style="height: 35px;"
                        class="me-2">
                    <span style="color: #952638;">INGAT.IN!</span>
                </a>

                {{-- Deskripsi: mx-auto agar di tengah saat mobile --}}
                <p class="text-white small mx-auto mx-lg-0" style="max-width: 350px; line-height: 1.6;">
                    Mitra informasi terpercaya komunitas RT 19. Akses jadwal kegiatan, status pendaftaran, dan arsip
                    dokumentasi dengan mudah dan transparan.
                </p>
            </div>

            {{-- WRAPPER NAVIGASI & KONTAK --}}
            {{-- Ini memastikan sisa ruang dibagi rata --}}

            {{-- Kolom 2: Navigasi Cepat --}}
            {{-- Mobile: Setengah Layar (col-6) | Desktop: 3/12 --}}
            <div class="col-6 col-md-4 col-lg-3">
                <h5 class="fw-semibold mb-3 mb-lg-4 text-uppercase fs-6 fs-md-5" style="color: #952638;">Navigasi</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}"
                            class="text-white text-decoration-none hover-accent small">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('warga.calendar') }}"
                            class="text-white text-decoration-none hover-accent small">Kalender Kegiatan</a></li>
                    <li class="mb-2"><a href="{{ route('daftar') }}"
                            class="text-white text-decoration-none hover-accent small">Daftar Kegiatan</a></li>
                    <li class="mb-2"><a href="{{ route('about') }}"
                            class="text-white text-decoration-none hover-accent small">Tentang Kami</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak --}}
            {{-- Mobile: Setengah Layar (col-6) | Desktop: 4/12 --}}
            <div class="col-6 col-md-8 col-lg-4">
                <h5 class="fw-semibold mb-3 mb-lg-4 text-uppercase fs-6 fs-md-5" style="color: #952638;">Alamat</h5>
                <ul class="list-unstyled text-white small">
                    <li class="d-flex align-items-start mb-3 gap-2">
                        <i class="bi bi-geo-alt-fill mt-1 flex-shrink-0" style="color: #952638;"></i>
                        <span class="text-start">RT 19, Perumahan Villa Kenali, Kecamatan Alam Barajo, Kelurahan Mayang Mangurai, Kota Jambi <br> Jambi, Indonesia</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Divider dan Copyright --}}
        <div
            class="border-top border-white-50 mt-4 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-center text-md-start">
            <p class="text-white-50 small mb-0">
                &copy; {{ date('Y') }} INGAT.IN! RT.19. All rights reserved.
            </p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="#" class="text-white-50 text-decoration-none hover-accent small">Kebijakan Privasi</a>
                <a href="#" class="text-white-50 text-decoration-none hover-accent small">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

@push('styles')
    <style>
        /* CSS untuk efek hover pada link footer */
        .hover-accent:hover {
            color: #FFC107 !important;
            /* Warna aksen kuning saat hover */
            padding-left: 5px;
            /* Efek geser sedikit */
        }

        .hover-accent {
            transition: all 0.2s ease;
            display: inline-block;
            /* Diperlukan agar transform/padding berfungsi */
        }
    </style>
@endpush
