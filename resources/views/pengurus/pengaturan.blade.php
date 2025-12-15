@extends('layouts.admin')
@section('title', 'Ubah Password Pengurus')
@section('content')
    <div class="container py-4">
        <h1 class="fw-semibold mb-4 text-center" style="color: #343A40;">Pengaturan Akun</h1>

        {{-- Pesan Sukses Global --}}
        @if (session('success'))
            <div class="alert alert-success col-md-6 mx-auto mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger col-md-6 mx-auto mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            {{-- KOLOM GANTI PASSWORD --}}
            <div class="col-md-6">
                <div class="card shadow border-0" style="border-radius: 12px;">
                    <div class="card-header text-white fw-semibold py-3 text-center"
                        style="background-color: #88304E; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        <i class="bi bi-shield-lock-fill me-2"></i> Ganti Kata Sandi
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.pengurus.update') }}">
                            @csrf

                            {{-- Password Lama --}}
                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold text-secondary">Kata Sandi
                                    Lama</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-key-fill"></i></span>
                                    <input type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="current_password">
                                        <i class="bi bi-eye-slash-fill"></i>
                                    </button>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password Baru --}}
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold text-secondary">Kata Sandi Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password">
                                        <i class="bi bi-eye-slash-fill"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold text-secondary">Konfirmasi
                                    Kata Sandi Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-patch-check-fill"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password_confirmation">
                                        <i class="bi bi-eye-slash-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn text-white fw-semibold py-2"
                                    style="background-color: #88304E;">
                                    <i class="bi bi-save me-1"></i> Perbarui Kata Sandi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-password');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bi-eye-slash-fill');
                        icon.classList.add('bi-eye-fill');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bi-eye-fill');
                        icon.classList.add('bi-eye-slash-fill');
                    }
                });
            });
        });
    </script>
@endsection
