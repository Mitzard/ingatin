@extends('layouts.admin')

@section('title', 'Tambah Kegiatan Baru')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-10 col-md-12 mx-auto">

                <h2 class="fw-bold mb-4" style="color:#88304E">
                    <i class="bi bi-calendar-plus me-2"></i> Formulir Tambah Kegiatan
                </h2>

                {{-- Menampilkan pesan sukses dari Controller --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-lg border-0" style="border-radius: 12px;">
                    <div class="card-body p-4">

                        {{-- Form Aksi diarahkan ke ScheduleController@create --}}
                        <form action="{{ route('schedules.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- 1. JUDUL KEGIATAN (title) --}}
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Judul Kegiatan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="Contoh: Kerja Bakti Akbar" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- 2. TANGGAL MULAI (start_date) & JAM MULAI (start_time) --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Waktu Mulai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control @error('start') is-invalid @enderror"
                                            name="start" value="{{ old('start') }}" required>
                                        <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                            name="start_time" value="{{ old('start_time') }}" required>
                                    </div>
                                    @error('start')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    @error('start_time')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 3. TANGGAL SELESAI (end_date) & JAM SELESAI (end_time) --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Waktu Selesai (Opsional)</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control @error('end') is-invalid @enderror"
                                            name="end" value="{{ old('end') }}">
                                        <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                            name="end_time" value="{{ old('end_time') }}">
                                    </div>
                                    <div class="form-text text-muted">Kosongkan jika kegiatan seharian penuh.</div>
                                    @error('end')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    @error('end_time')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- tambah jam kegiatan --}}

                            {{-- 4. LOKASI (lokasi) --}}
                            <div class="mb-3">
                                <label for="lokasi" class="form-label fw-semibold">Lokasi <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                    id="lokasi" name="lokasi" value="{{ old('lokasi') }}"
                                    placeholder="Contoh: Balai Warga RT.19" required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 5. WARNA KALENDER (color) --}}
                            <div class="mb-3">
                                <label for="color" class="form-label fw-semibold">Warna Kalender</label>
                                <input type="color" class="form-control form-control-color" id="color" name="color"
                                    value="{{ old('color', '#dc3545') }}">
                            </div>

                            {{-- 6. DESKRIPSI (description) --}}
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Deskripsi Kegiatan</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Jelaskan detail, tujuan, dan instruksi kegiatan di sini...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image_flyer" class="form-label fw-semibold">Upload Flyer / Poster (Opsional)</label>
                                <input type="file" class="form-control @error('image_flyer') is-invalid @enderror"
                                    id="image_flyer" name="image_flyer" accept="image/*">
                                <div class="form-text text-muted">
                                    Format yang didukung: JPG, PNG, JPEG. Ukuran maks: 2MB.
                                </div>
                                @error('image_flyer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i
                                        class="bi bi-arrow-left"></i> Kembali</a>

                                <button type="submit" class="btn fw-semibold"
                                    style="background-color: #88304E; color: white;">
                                    <i class="bi bi-calendar-plus me-1"></i> Tambahkan ke Jadwal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
