@extends('layouts.app')
@section('title', 'Detail Kegiatan: '. $activity->title)
@section('content')
    <div class="container" style="padding: 7rem 0rem 2rem;">
        <a href="{{ route('daftar') }}" class="btn btn-sm btn-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>
            Kembali ke Daftar Kegiatan</a>

        <div class="card shadow-lg border-0" id="activity-detail-card" style="border-radius: 12px; overflow: hidden;">
            <div class="row g-0">

                {{-- KOLOM KIRI: FLYER BESAR (60%) --}}
                <div class="col-md-4 bg-dark d-flex align-items-center justify-content-center overflow-hidden position-relative"
                    style="min-height: 500px; max-height: 600px; background-color: #212529;">

                    {{-- LOGIKA GAMBAR --}}
                    @if ($activity->image_flyer_path && Storage::disk('public')->exists($activity->image_flyer_path))
                        {{-- SKENARIO 1: ADA GAMBAR --}}
                        {{-- Gunakan w-100 h-100 dan object-fit-contain agar gambar tidak gepeng/terpotong --}}
                        <img src="{{ asset('storage/' . $activity->image_flyer_path) }}" alt="Flyer {{ $activity->title }}"
                            class="img-fluid w-100 h-100"
                            style="object-fit: contain; position: absolute; top: 0; left: 0; backdrop-filter: blur(10px);">

                        {{-- Tips: Background blur di belakang gambar utama biar estetik jika gambar kecil --}}
                        <div
                            style="position: absolute; width: 100%; height: 100%; 
                                    background-image: url('{{ asset('storage/' . $activity->image_flyer_path) }}'); 
                                    background-size: cover; filter: blur(20px); opacity: 0.3; z-index: 0;">
                        </div>

                        {{-- Gambar Utama di atas blur --}}
                        <img src="{{ asset('storage/' . $activity->image_flyer_path) }}"
                            class="position-relative w-100 h-100" style="object-fit: contain; z-index: 1;"
                            alt="Flyer Utama">
                    @else
                        {{-- SKENARIO 2: TIDAK ADA GAMBAR (KOSONG) --}}
                        {{-- Tampilan Placeholder CSS Murni (Lebih Konsisten daripada gambar placeholder external) --}}
                        <div class="text-center text-white p-5">
                            <div class="mb-3">
                                <i class="bi bi-image text-secondary" style="font-size: 5rem; opacity: 0.5;"></i>
                            </div>
                            <h5 class="fw-semibold text-secondary" style="letter-spacing: 1px;">FLYER BELUM TERSEDIA</h5>
                            <p class="small text-muted">Hubungi panitia untuk informasi detail poster.</p>
                        </div>
                    @endif

                </div>

                {{-- KOLOM KANAN: DETAIL & TOMBOL AKSI (40%) --}}
                <div class="col-md-8 p-4">
                    <h1 class="fw-semibold mb-2" style="color:#952638;">{{ $activity->title }}</h1>
                    <p class="text-muted small mb-4">Dibuat oleh Pengurus pada
                        {{ $activity->created_at->translatedFormat('d M Y') }}</p>
                    <hr>

                    {{-- INFO UTAMA --}}
                    <h5 class="fw-semibold mt-4" style="color: #952638;"><i class="bi bi-geo-alt-fill me-2"></i> Lokasi:
                    </h5>
                    <p class="lead">{{ $activity->lokasi }}</p>

                    <h5 class="fw-semibold mt-3" style="color: #952638"><i class="bi bi-clock-fill me-2"></i> Waktu:</h5>
                    <div class="lead">
                        {{-- Mulai --}}
                        <div class="mb-2">
                            <span class="d-block text-muted" style="font-size: 0.8rem; font-weight: 600;">MULAI:</span>
                            {{ $activity->start->translatedFormat('l, d F Y') }}
                            <small class="text-muted ms-1">pukul {{ $activity->start->format('H:i') }} WIB</small>
                        </div>

                        {{-- Selesai (Jika ada) --}}
                        @if ($activity->end)
                            <div>
                                <span class="d-block text-muted"
                                    style="font-size: 0.8rem; font-weight: 600;">SELESAI:</span>
                                {{ $activity->end->translatedFormat('l, d F Y') }}
                                <small class="text-muted ms-1">pukul {{ $activity->end->format('H:i') }} WIB</small>
                            </div>
                        @else
                            <div>
                                <span class="d-block text-muted"
                                    style="font-size: 0.8rem; font-weight: 600;">SELESAI:</span>
                                - (Selesai pada hari yang sama)
                            </div>
                        @endif
                    </div>

                    <h5 class="fw-semibold mt-3" style="color: #952638"><i class="bi bi-file-text me-2"></i> Deskripsi:
                    </h5>
                    <p class="small text-secondary">{{ $activity->description }}</p>

                    <hr class="my-4">

                    {{-- AREA AKSI DINAMIS --}}
                    <div id="action-area" class="mt-4">

                        {{-- Tampilan Pesan Flash (Tambahkan di bagian atas view, di bawah container) --}}
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        {{-- Status Pendaftaran --}}
                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-semibold me-3">Status Anda:</span>
                            <span id="reg-status-text"
                                class="fw-semibold p-2 rounded 
            {{ $isRegistered ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                {{ $isRegistered ? 'TERDAFTAR' : 'BELUM TERDAFTAR' }}
                            </span>
                        </div>

                        {{-- Tombol Aksi (Form Submission) --}}
                        <div id="button-container">
                            @if ($activity->status == 'Selesai' || $activity->status == 'Berlangsung')
                                {{-- Non-interaktif jika sudah selesai/sedang berlangsung --}}
                                <button class="btn btn-secondary fw-semibold w-100 disabled" disabled>
                                    Kegiatan Sudah Selesai / Pendaftaran Ditutup
                                </button>
                            @elseif ($isRegistered)
                                {{-- TOMBOL BATALKAN --}}
                                <p class="fw-semibold text-success">Kamu sudah terdaftar.</p>

                                <button type="button" class="btn btn-warning fw-semibold w-100" data-bs-toggle="modal"
                                    data-bs-target="#modalBatal">
                                    <i class="bi bi-x-octagon-fill me-1"></i> BATALKAN PENDAFTARAN
                                </button>
                            @else
                                {{-- TOMBOL DAFTAR --}}
                                <button type="button" class="btn btn-danger fw-semibold w-100" data-bs-toggle="modal"
                                    data-bs-target="#modalDaftar">
                                    <i class="bi bi-pencil-square me-1"></i> DAFTAR SEKARANG
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDaftar" tabindex="-1" aria-labelledby="modalDaftarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                {{-- Form dipindah ke dalam Modal --}}
                <form action="{{ route('schedules.register.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="activity_id" value="{{ $activity->id }}">

                    <div class="modal-header text-white" style="background-color: #952638">
                        <h5 class="modal-title fw-semibold" id="modalDaftarLabel">
                            <i class="bi bi-question-circle-fill me-2"></i>Konfirmasi Pendaftaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <p class="mb-1">Apakah Anda yakin ingin mendaftar pada kegiatan:</p>
                        <h5 class="fw-semibold text-dark">{{ $activity->title }}</h5>
                        <p class="text-muted small mt-2">Pastikan Anda bisa hadir pada waktu yang ditentukan.</p>
                    </div>

                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-dark fw-semibold border px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger px-4 fw-semibold">
                            Ya, Saya Mendaftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================== --}}
    {{-- MODAL KONFIRMASI BATAL --}}
    {{-- =========================== --}}
    <div class="modal fade" id="modalBatal" tabindex="-1" aria-labelledby="modalBatalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                {{-- Form dipindah ke dalam Modal --}}
                <form action="{{ route('schedules.register.destroy', $activity->id) }}" method="POST">
                    @csrf
                    {{-- Method DELETE/Destroy biasanya butuh directive method jika route Anda pake DELETE, 
                         tapi jika route Anda POST, biarkan saja @csrf. 
                         Jika route Anda DELETE, uncomment baris bawah: --}}
                    {{-- @method('DELETE') --}}

                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-semibold" id="modalBatalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Batalkan Pendaftaran
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <p class="mb-1">Apakah Anda yakin ingin membatalkan keikutsertaan di:</p>
                        <h5 class="fw-semibold text-dark">{{ $activity->title }}?</h5>
                        <p class="text-danger small mt-2 fw-semibold">Tindakan ini akan menghapus nama Anda dari daftar
                            hadir.
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-dark border fw-semibold px-4"
                            data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-warning px-4 fw-semibold">
                            Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI (DAFTAR & BATALKAN) --}}
@endsection
