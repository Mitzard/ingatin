@extends('layouts.admin')

@section('title', 'Edit Kegiatan: ' . $activity->title)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-10 col-md-12 mx-auto">

                <h2 class="fw-bold mb-4" style="color: #343A40;">
                    <i class="bi bi-pencil-square me-2"></i> Edit Kegiatan
                </h2>

                {{-- Menampilkan pesan sukses/error --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">Terdapat kesalahan dalam pengisian formulir.</div>
                @endif

                <div class="card shadow-lg border-0" style="border-radius: 12px;">
                    <div class="card-body p-4">

                        {{-- Form Aksi diarahkan ke ScheduleController@update --}}
                        <form action="{{ route('schedules.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{-- Laravel memerlukan metode PUT/PATCH untuk update sumber daya --}}
                            @method('PUT')

                            {{-- 1. JUDUL KEGIATAN (title) --}}
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Judul Kegiatan <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $activity->title) }}"
                                    placeholder="Contoh: Kerja Bakti Akbar" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- WAKTU MULAI --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Waktu Mulai <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {{-- Ambil tanggal saja (Y-m-d) --}}
                                        <input type="date" class="form-control" name="start" 
                                            value="{{ old('start', \Carbon\Carbon::parse($activity->start)->format('Y-m-d')) }}" required>
                                        
                                        {{-- Ambil jam saja (H:i) --}}
                                        <input type="time" class="form-control" name="start_time" 
                                            value="{{ old('start_time', \Carbon\Carbon::parse($activity->start)->format('H:i')) }}" required>
                                    </div>
                                </div>

                                {{-- WAKTU SELESAI --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Waktu Selesai</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="end" 
                                            value="{{ old('end', $activity->end ? \Carbon\Carbon::parse($activity->end)->format('Y-m-d') : '') }}">
                                        
                                        <input type="time" class="form-control" name="end_time" 
                                            value="{{ old('end_time', $activity->end ? \Carbon\Carbon::parse($activity->end)->format('H:i') : '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 4. LOKASI (lokasi) --}}
                            <div class="mb-3">
                                <label for="lokasi" class="form-label fw-bold">Lokasi <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                    id="lokasi" name="lokasi" value="{{ old('lokasi', $activity->lokasi) }}"
                                    placeholder="Contoh: Balai Warga RT.19" required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 5. DESKRIPSI (description) --}}
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">Deskripsi Kegiatan</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Jelaskan detail, tujuan, dan instruksi kegiatan di sini...">{{ old('description', $activity->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="image_flyer" class="form-label fw-bold">Update Flyer (Opsional)</label>

                                {{-- Tampilkan flyer lama jika ada --}}
                                @if ($activity->image_flyer_path)
                                    <div class="mb-2 p-2 border rounded bg-light" style="width: fit-content;">
                                        <div class="small text-muted mb-1">Flyer Saat Ini:</div>
                                        <img src="{{ asset('storage/' . $activity->image_flyer_path) }}" alt="Flyer Lama"
                                            class="img-fluid rounded" style="max-height: 150px;">
                                    </div>
                                @endif

                                <input type="file" class="form-control @error('image_flyer') is-invalid @enderror"
                                    id="image_flyer" name="image_flyer" accept="image/*">
                                <div class="form-text text-muted">
                                    Biarkan kosong jika tidak ingin mengubah flyer. Max: 2MB.
                                </div>
                                @error('image_flyer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('kelola.kegiatan') }}" class="btn btn-secondary"><i
                                        class="bi bi-arrow-left"></i> Kembali</a>

                                <button type="submit" class="btn fw-bold" style="background-color: #952638; color: white;">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                @if ($activity->status == 'Selesai')
                    <div class="card shadow-lg mt-5 border-start border-5 border-success">
                        <div class="card-header bg-success text-white fw-bold">
                            <i class="bi bi-camera-fill me-2"></i> Tambah Dokumentasi Arsip
                        </div>
                        <div class="card-body p-4">
                            <p class="small text-muted">
                                Unggah foto dokumentasi kegiatan ini. Maksimal 2 MB per file.
                            </p>

                            <form action="{{ route('schedules.documentation.store', $activity->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="fileInput" class="form-label">Pilih Foto Kegiatan</label>
                                    <input type="file"
                                        class="form-control @error('document_file') is-invalid @enderror" id="fileInput"
                                        name="document_file" accept="image/*" required>
                                    <div class="form-text text-muted">File akan diverifikasi server (Max 2MB).</div>
                                    @error('document_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="caption" class="form-label">Keterangan Foto (Opsional)</label>
                                    <input type="text" class="form-control" name="caption"
                                        placeholder="Deskripsikan foto ini">
                                </div>

                                <button type="submit" class="btn btn-success fw-bold">
                                    Unggah Dokumentasi
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mt-4">
                        Dokumentasi hanya dapat ditambahkan setelah status kegiatan ini diubah menjadi Selesai.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
