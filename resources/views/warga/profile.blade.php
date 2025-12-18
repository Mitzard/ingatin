@extends('layouts.app')
@section('title', 'Lihat Profil')
@section('content')
    <div class="container py-5">
        <h1 class="fw-bold mb-4" style="color: #343A40;">Edit Profil Warga</h1>

        {{-- Pesan Sukses Global --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">Terdapat kesalahan dalam pengisian formulir.</div>
        @endif

        <div class="row">

            {{-- KOLOM KIRI: FOTO PROFIL DAN DATA DIRI --}}
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white fw-semibold">
                        <i class="bi bi-person-fill me-2"></i> Data Diri & Foto Profil
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Area Foto Profil --}}
                            <div class="text-center mb-4">

                                {{-- 1. AREA PREVIEW FOTO PROFIL --}}
                                <div class="position-relative d-inline-block">
                                    {{-- Kita beri ID "profile-preview" agar bisa diakses Javascript --}}
                                    @if (Auth::user()->profile_photo_path)
                                        <img id="profile-preview" src="{{ Auth::user()->profile_photo_path }}"
                                            alt="Foto Profil" class="rounded-circle mb-3 border border-3 shadow-sm"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <img id="profile-preview"
                                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_lengkap) }}&size=150"
                                            alt="Default Avatar" class="rounded-circle mb-3 border border-3 shadow-sm"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                    @endif
                                </div>

                                {{-- 2. INPUT FILE MODEL "WIDE BOX" --}}
                                <div class="mt-2">
                                    {{-- Label ini bertindak sebagai tombol input yang luas --}}
                                    <label for="profile_photo" class="form-label w-100 cursor-pointer">

                                        <div class="d-flex flex-column align-items-center justify-content-center p-4 border border-2 border-secondary border-opacity-25 rounded-3 bg-light hover-effect"
                                            style="border-style: dashed !important; cursor: pointer; transition: all 0.3s ease;">

                                            {{-- Icon Upload --}}
                                            <i class="bi bi-cloud-arrow-up-fill text-primary fs-2 mb-2"></i>

                                            {{-- Teks Instruksi --}}
                                            <span class="fw-semibold text-dark">Klik untuk ganti foto</span>
                                            <span class="small text-muted">Format: JPG/PNG. Maks: 2MB</span>

                                            {{-- Input File ASLI (Disembunyikan tapi berfungsi) --}}
                                            {{-- onchange="previewImage(event)" akan memicu script preview --}}
                                            <input type="file"
                                                class="d-none @error('profile_photo') is-invalid @enderror"
                                                id="profile_photo" name="profile_photo" accept="image/*"
                                                onchange="previewImage(this)">
                                        </div>
                                    </label>

                                    {{-- Pesan Error --}}
                                    @error('profile_photo')
                                        <div class="invalid-feedback d-block text-danger mt-2 fw-semibold">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            {{-- Input Data Diri (Nama, NIK, Email, No. HP) --}}
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                    name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->nama_lengkap) }}"
                                    required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik"
                                    value="{{ Auth::user()->nik }}">
                                {{-- disabled @readonly(true) --}}
                                {{-- <div class="form-text text-muted">NIK tidak dapat diubah setelah pendaftaran.</div> --}}
                            </div>

                            {{-- EMAIL --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- NOMOR HP --}}
                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor HP</label>
                                <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror"
                                    name="nomor_telepon" value="{{ old('nomor_telepon', Auth::user()->nomor_telepon) }}"
                                    required>
                                @error('nomor_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ALAMAT --}}
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Rumah</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="2">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- USIA --}}
                                <div class="col-md-4 mb-3">
                                    <label for="usia" class="form-label">Usia</label>
                                    <input type="number" class="form-control @error('usia') is-invalid @enderror"
                                        name="usia" value="{{ old('usia', Auth::user()->usia) }}">
                                    @error('usia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- JENIS KELAMIN --}}
                                <div class="col-md-4 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                        name="jenis_kelamin">
                                        <option value="" @selected(!Auth::user()->jenis_kelamin)>Pilih</option>
                                        <option value="Laki-laki" @selected(Auth::user()->jenis_kelamin == 'Laki-laki')>Laki-laki</option>
                                        <option value="Perempuan" @selected(Auth::user()->jenis_kelamin == 'Perempuan')>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PEKERJAAN --}}
                                <div class="col-md-4 mb-3">
                                    <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                    <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror"
                                        name="pekerjaan" value="{{ old('pekerjaan', Auth::user()->pekerjaan) }}">
                                    @error('pekerjaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 fw-semibold">Simpan Perubahan Data</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: GANTI PASSWORD --}}
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white fw-semibold">
                        <i class="bi bi-lock-fill me-2"></i> Ganti Kata Sandi
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Kata Sandi Lama</label>
                                <input type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-danger fw-semibold mt-3">Perbarui Kata Sandi</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function previewImage(input) {
            var preview = document.getElementById('profile-preview');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Mengganti src gambar dengan hasil bacaan file lokal
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    {{-- 4. STYLE TAMBAHAN (Opsional untuk efek hover) --}}
    <style>
        .hover-effect:hover {
            background-color: #e9ecef !important;
            /* Abu-abu sedikit lebih gelap saat di-hover */
            border-color: #0d6efd !important;
            /* Border jadi biru saat di-hover */
        }
    </style>
@endsection
