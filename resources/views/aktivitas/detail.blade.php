@extends('layouts.app')
@section('title', 'Detail Kegiatan: ' . $activity->title)
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
                    @if ($activity->image_flyer_path)
                        {{-- SKENARIO 1: ADA GAMBAR --}}

                        {{-- Background Blur (Efek Estetik) --}}
                        {{-- PERBAIKAN 2: Langsung panggil variabelnya, hapus asset('storage/...') --}}
                        <div
                            style="position: absolute; width: 100%; height: 100%; 
                    background-image: url('{{ $activity->image_flyer_path }}'); 
                    background-size: cover; filter: blur(20px); opacity: 0.3; z-index: 0;">
                        </div>

                        {{-- Gambar Utama --}}
                        {{-- PERBAIKAN 3: Langsung panggil variabelnya --}}
                        <img src="{{ $activity->image_flyer_path }}" class="position-relative w-100 h-100"
                            style="object-fit: contain; z-index: 1;" alt="Flyer {{ $activity->title }}">
                    @else
                        {{-- SKENARIO 2: TIDAK ADA GAMBAR (KOSONG) --}}
                        <div class="text-center text-white p-5 position-relative" style="z-index: 2;">
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
                                <button type="button" class="btn fw-semibold text-white w-100" style="background-color: #952638;" data-bs-toggle="modal"
                                    data-bs-target="#modalDaftar">
                                    <i class="bi bi-pencil-square me-1"></i> DAFTAR SEKARANG
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            @if ($activity->documentations && $activity->documentations->count() > 0)
                <div class="mt-5">
                    <h3 class="fw-bold mb-4" style="color: #952638; border-left: 5px solid #952638; padding-left: 15px;">
                        Galeri Dokumentasi
                    </h3>

                    <div class="row g-3">
                        @foreach ($activity->documentations as $doc)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden position-relative group-hover">
                                    {{-- Gambar Thumbnail --}}
                                    {{-- Kita gunakan ratio 1x1 atau 4x3 agar rapi --}}
                                    <div style="aspect-ratio: 1/1; overflow: hidden; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#modalDoc{{ $doc->id }}">
                                        <img src="{{ $doc->file_path }}" alt="Dokumentasi" class="w-100 h-100"
                                            style="object-fit: cover; transition: transform 0.3s ease;"
                                            onmouseover="this.style.transform='scale(1.1)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    </div>

                                    {{-- Caption Kecil (Opsional) --}}
                                    @if ($doc->caption)
                                        <div class="card-footer bg-white border-0 py-2">
                                            <small class="text-muted text-truncate d-block">{{ $doc->caption }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Modal Preview Gambar Full (Per Gambar) --}}
                            <div class="modal fade" id="modalDoc{{ $doc->id }}" tabindex="-1" aria-hidden="true">
                                {{-- Hapus modal-lg jika ingin modal menyesuaikan gambar, atau ganti modal-xl untuk layar lebar --}}
                                {{-- Tapi kuncinya ada di CSS gambar di bawah --}}
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content bg-transparent border-0 shadow-none">

                                        {{-- Tombol Close (Diposisikan di kanan atas gambar) --}}
                                        <div class="modal-header border-0 p-0 mb-2">
                                            <button type="button" class="btn-close btn-close-white ms-auto"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        {{-- Body Modal --}}
                                        <div
                                            class="modal-body text-center p-0 d-flex flex-column align-items-center justify-content-center">

                                            {{-- GAMBAR --}}
                                            {{-- style="max-height: 85vh" = Maksimal tinggi 85% dari layar --}}
                                            {{-- style="width: auto" = Lebar menyesuaikan agar tidak gepeng --}}
                                            <img src="{{ $doc->file_path }}" class="img-fluid rounded shadow-lg"
                                                alt="Full Preview"
                                                style="max-height: 80vh; width: auto; max-width: 100%; object-fit: contain;">

                                            {{-- CAPTION --}}
                                            @if ($doc->caption)
                                                <div class="mt-2 text-white bg-dark bg-opacity-75 p-2 rounded d-inline-block"
                                                    style="max-width: 80%;">
                                                    {{ $doc->caption }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Pesan Jika Belum Ada Dokumentasi (Opsional, bisa dihapus jika ingin hidden) --}}
                <div class="mt-5 text-center text-muted opacity-50">
                    <i class="bi bi-camera-fill fs-3"></i>
                    <p class="small">Belum ada dokumentasi untuk kegiatan ini.</p>
                </div>
            @endif
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
