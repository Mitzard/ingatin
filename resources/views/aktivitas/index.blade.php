@extends('layouts.app')
@section('title', 'Daftar Kegiatan')
@section('content')
    {{-- Injeksi CSS Khusus Halaman ini (untuk ketebalan dan konsistensi) --}}
    @push('styles')
        <style>
            /* 1. KETERANGAN UTAMA CARD */
            .card-kegiatan {
                border-radius: 12px !important;
                border-color: #952638;
                background-color: #ffffff !important;
                /* Warna Latar Card (Gelap) */
                color: white;
                transition: transform 0.2s ease;
            }

            .card-kegiatan:hover {
                transform: translateY(-3px);
                /* Efek Hover Tipis (UX) */
                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4) !important;
            }

            /* 2. AREA FOOTER CARD */
            .card-footer-kegiatan {
                background-color: #888888 !important;
                /* Warna Footer (Lebih Gelap dari Body Card) */
                border-top: 1px solid rgba(255, 255, 255, 0.066) !important;
            }

            /* 3. JUDUL DAN ICON */
            .card-title-kegiatan {
                color: #952638 !important;
                /* Aksen Kuning (Warna Tegas) */
                font-weight: 600 !important;
                /* Bold */
                font-size: 1.2rem;
            }

            .card-kegiatan .list-unstyled i {
                color: #952638 !important;
                /* Icon menggunakan Merah (Aksen) */
                /* font-weight: 500; */
            }

            /* Teks Status (Disesuaikan agar lebih menonjol) */
            .text-warning-status {
                color: #31312f !important;
            }

            /* Belum Terdaftar */
            .text-success-status {
                color: #8bff89 !important;
            }

            /* Terdaftar (Hijau Konsisten) */
            .text-secondary-status {
                color: #e12b3d !important;
            }

            /* Selesai/Sedang Berlangsung */
            .text-info-status {
                color: #0dcaf0 !important;
            }
        </style>
    @endpush

    <div class="container py-5">

        <div class="row mb-1 mt-3">
            <div class="col-md-9 mt-5">
                <h2 class="display-7 fw-semibold" style="color: #952638;">Daftar Kegiatan Komunitas</h2>
                <p class="lead" style="font-size: 90%">Ayo daftar dan aktifkan kontribusimu di komunitas RT.19!</p>
            </div>
            {{-- <div class="col-md-3 d-flex align-items-center justify-content-end">
                <a href="#" class="btn btn-sm btn-outline-secondary fw-bold">Filter Tanggal</a>
            </div> --}}
        </div>

        <div class="card mb-4 border-danger border-3 shadow-sm">
            <div class="card-body">
                <form action="{{ route('daftar') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Cari Judul, Lokasi, atau Status..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn text-white fw-bold" style="background-color: #952638;"><i
                            class="bi bi-search"></i></button>
                    @if ($search)
                        <a href="{{ route('kelola.kegiatan') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tampilan Pesan Flash --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button
                    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button
                    type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif

        {{-- Menggunakan row-cols-md-3 untuk 3 card per baris --}}
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($activities as $activity)
                @php
                    // --- 1. Ambil Status ---
                    $activityStatus = $activity->status;
                    $isRegistered = $activity->registrations->isNotEmpty();

                    // --- 2. Inisialisasi Tampilan ---
                    $buttonText = '';
                    $buttonClass = '';
                    $statusText = '';
                    $statusTextColor = '';

                    // --- 3. Logika Penentuan Status & Tombol ---
                    if ($activityStatus == 'finished') {
                        $statusText = 'Selesai';
                        $statusTextColor = 'text-secondary-status';
                        $buttonText = 'Lihat Dokumentasi';
                        $buttonClass = 'btn-secondary';
                    } elseif ($activityStatus == 'ongoing') {
                        $statusText = 'Sedang Berlangsung';
                        $statusTextColor = 'text-info-status';
                        $buttonText = 'Tidak Bisa Mendaftar';
                        $buttonClass = 'btn-secondary';
                    } elseif ($isRegistered) {
                        $statusText = 'Terdaftar';
                        $statusTextColor = 'text-success-status';
                        $buttonText = 'Batalkan';
                        $buttonClass = 'btn-warning';
                    } else {
                        $statusText = 'Belum Terdaftar';
                        $statusTextColor = 'text-warning-status';
                        $buttonText = 'Daftar';
                        $buttonClass = 'btn-danger';
                    }
                @endphp

                <div class="col">
                    <div class="card card-kegiatan h-100 border-4 shadow-lg overflow-hidden">
                        <div class="card-body p-4 flex-grow-1 position-relative">
                            <h5 class="card-title card-title-kegiatan mb-3">{{ $activity->title }}</h5>

                            <ul class="list-unstyled fw-semibold mb-3 small text-black">
                                <li><i class="bi bi-geo-alt-fill me-2"></i> Lokasi: {{ $activity->lokasi }}</li>
                                <li class="d-flex align-items-start mt-1">
                                    <i class="bi bi-calendar-check-fill me-2 mt-1"></i>
                                    <div>
                                        {{-- Waktu Mulai --}}
                                        <div>
                                            {{ $activity->start->translatedFormat('d M Y') }}
                                            <small class="text-muted">({{ $activity->start->format('H:i') }} WIB)</small>
                                        </div>

                                        {{-- Waktu Selesai (Cek jika ada) --}}
                                        @if ($activity->end)
                                            <div class="text-muted" style="font-size: 0.9em;">
                                                s/d
                                                {{ $activity->end->translatedFormat('d M Y') }}
                                                <small>({{ $activity->end->format('H:i') }} WIB)</small>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                                <li class="mt-2">
                                    <span
                                        class="badge fw-semibold rounded-pill 
                                    @if ($activityStatus == 'Selesai') bg-danger 
                                    @elseif($activityStatus == 'Berlangsung') bg-success 
                                    @else bg-info @endif">
                                        {{ strtoupper($activityStatus) }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        {{-- FOOTER CARD: Tombol Aksi --}}
                        <div class="card-footer card-footer-kegiatan p-3 d-flex justify-content-between align-items-center">

                            <span class="small fw-semibold status-warga {{ $statusTextColor }}">
                                {{ $statusText }}
                            </span>

                            {{-- Tombol DAFTAR / BATALKAN --}}
                            @if ($activityStatus == 'Selesai' || $activityStatus == 'Berlangsung')
                                <a href="{{ route('activity.show', $activity->id) }}"
                                    class="btn btn-sm btn-info fw-semibold">
                                    Dokumentasi Kegiatan
                                </a>
                            @elseif ($isRegistered)
                                <form action="{{ route('schedules.register.destroy', $activity->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin batalkan pendaftaran?');"
                                    class="d-flex align-items-center">
                                    @csrf
                                    <a href="{{ route('activity.show', $activity->id) }}"
                                        class="btn btn-sm btn-info fw-semibold me-2">
                                        Detail Kegiatan
                                    </a>
                                    {{-- <button type="submit" class="btn btn-sm fw-semibold {{ $buttonClass }}"
                                        style="color: white;">
                                        {{ $buttonText }}
                                    </button> --}}
                                </form>
                            @else
                                <form action="{{ route('schedules.register.store') }}" method="POST"
                                    class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                    <a href="{{ route('activity.show', $activity->id) }}"
                                        class="btn btn-sm btn-info fw-semibold me-2">
                                        Detail Kegiatan
                                    </a>
                                    {{-- <button type="submit" class="btn btn-sm fw-semibold {{ $buttonClass }}"
                                        style="color: white;">
                                        {{ $buttonText }}
                                    </button> --}}
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginasi --}}
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div>
                {{-- Informasi pagination opsional --}}
                Menampilkan {{ $activities->firstItem() }} sampai {{ $activities->lastItem() }} dari
                {{ $activities->total() }}
                Total Pendaftar
            </div>
            <div>
                {{ $activities->links() }}
            </div>
        </div>

    </div>
@endsection
