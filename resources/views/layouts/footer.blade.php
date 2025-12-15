<footer class="mt-auto pt-5 pb-3 position-relative overflow-hidden" style="background-color: #282828; color: #E9ECEF;">
    <div class="container position-relative" style="z-index: 10;">
        <div class="row g-5"> 

            {{-- Kolom 1: Logo dan Deskripsi --}}
            <div class="col-12 col-lg-6 mb-4 mb-lg-0"> 
                
                {{-- Logo dan Judul (Menggunakan Struktur yang Dikoreksi) --}}
                <a href="{{ route('home') }}" class="fs-4 fw-semibold mb-3 d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/logo-ingatin.png') }}" alt="Logo Ingat.in" style="height: 35px;" class="me-2">
                    <span style="color: #952638;">INGAT.IN!</span> 
                </a>
                
                <p class="text-white small text-start" style="max-width: 350px; line-height: 1.6;">
                    Mitra informasi terpercaya komunitas RT.19. Akses jadwal kegiatan, status pendaftaran, dan arsip
                    dokumentasi dengan mudah dan transparan.
                </p>
            </div>
            
            {{-- Kolom 2: Navigasi Cepat --}}
            <div class="col-6 col-md-3 col-lg-3">
                <h5 class="fw-semibold mb-4 text-uppercase" style="color: #952638;">Navigasi Cepat</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-white text-decoration-none hover-accent">Beranda</a></li>
                    
                    {{-- ROUTE CORRECTED: Kalender Warga --}}
                    <li class="mb-2"><a href="{{ route('warga.calendar')}}" class="text-white text-decoration-none hover-accent">Kalender Kegiatan</a></li>
                    
                    {{-- ROUTE CORRECTED: Daftar Kegiatan --}}
                    <li class="mb-2"><a href="{{ route('daftar')}}" class="text-white text-decoration-none hover-accent">Daftar Kegiatan</a></li>
                    
                    <li class="mb-2"><a href="{{ route('login')}}" class="text-white text-decoration-none hover-accent">Masuk Akun</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak --}}
            <div class="col-6 col-md-3 col-lg-3">
                <h5 class="fw-semibold mb-4 text-uppercase" style="color: #952638;">Hubungi Kami</h5>
                <ul class="list-unstyled text-white small">
                    <li class="d-flex align-items-start mb-3 gap-2">
                        <i class="bi bi-geo-alt-fill mt-1" style="color: #952638;"></i>
                        <span class="text-start">RT.19, Kel. Mayang Mangurai <br> Jambi, Indonesia</span>
                    </li>
                    <li class="d-flex align-items-center mb-3 gap-2">
                        <i class="bi bi-envelope-fill" style="color: #952638;"></i>
                        <span>Email: [kontak@rt19.com]</span>
                    </li>
                    <li class="d-flex align-items-center mb-3 gap-2">
                        <i class="bi bi-whatsapp" style="color: #952638;"></i>
                        <span>HP: [08xxxxxxxxxx]</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Divider dan Copyright --}}
        <div class="border-top border-white-50 mt-5 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <p class="text-white-50 small mb-0">
                &copy; {{ date('Y') }} INGAT.IN! RT.19. All rights reserved.
            </p>
            <div class="d-flex gap-3">
                <a href="#" class="text-white-50 text-decoration-none hover-accent small">Kebijakan Privasi</a>
                <a href="#" class="text-white-50 text-decoration-none hover-accent small">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

{{-- Tambahkan CSS Kustom ke layouts/app.blade.php untuk efek hover --}}
@push('styles')
<style>
    /* CSS untuk efek hover pada link footer */
    .hover-accent:hover {
        color: #FFC107 !important; /* Warna aksen kuning saat hover */
        transform: translateX(5px);
    }
    .hover-accent {
        transition: all 0.2s ease;
    }
    
    /* DEFINISI UNTUK ANIMASI CIRCLES (Hanya jika Anda menggunakan CSS Kustom) */
    .circles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        margin: 0;
        padding: 0;
        list-style: none;
        background: none; /* Latar belakang seharusnya hanya untuk visual, bukan logika */
    }

    .circles li {
        position: absolute;
        display: block;
        list-style: none;
        background: rgba(255, 255, 255, 0.15); /* Lingkaran transparan */
        animation: animate 25s linear infinite;
        bottom: -150px; /* Mulai dari bawah */
    }

    @keyframes animate {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
            border-radius: 0;
        }

        100% {
            transform: translateY(-1000px) rotate(720deg); /* Naik ke atas dan berputar */
            opacity: 0;
            border-radius: 50%;
        }
    }
    /* Anda perlu memindahkan style inline di li ke dalam kode CSS ini
       untuk meniru posisi dan ukuran awal yang berbeda. */
</style>
@endpush