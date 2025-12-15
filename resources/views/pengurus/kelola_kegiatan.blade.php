@extends('layouts.admin')

@section('title', 'Kelola Kegiatan')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: #343A40;">Daftar Kegiatan</h2>
            {{-- <a href="{{ route('schedules.create') }}" class="btn btn-custom d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> Tambah Kegiatan
            </a> --}}
        </div>

        {{-- Area Search dan Filter --}}
        <div class="card mb-4  border-2 shadow-sm" style="border-color: #952638;">
            <div class="card-body">
                <form action="{{ route('kelola.kegiatan') }}" method="GET" class="d-flex">
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

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabel Kegiatan --}}
        <div class="card shadow-sm  border-2" style="border-color: #952638;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th scope="col">No </th>
                                <th scope="col">Judul Kegiatan</th>
                                <th scope="col">Waktu Mulai</th>
                                <th scope="col">Lokasi</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Aksi</th>
                                <th scope="col" class="text-center">Pendaftar</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($activities as $activity)
                                <tr>
                                    <td class="text-center">{{ $activities->firstItem() + $loop->index }}</td>
                                    <td>{{ $activity->title }}</td>
                                    <td class="text-center">
                                        {{ $activity->start->translatedFormat('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $activity->start->translatedFormat('H:i') }}
                                            WIB</small>
                                    </td>
                                    <td>{{ $activity->lokasi }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge 
                                        @if ($activity->status == 'Segera') bg-warning text-dark 
                                        @elseif($activity->status == 'Berlangsung') bg-info text-dark 
                                        @else bg-success text-dark @endif">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('schedules.edit', $activity->id) }}"
                                            class="btn btn-sm btn-primary me-2" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $activity->id }}"
                                            title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('participation.show', $activity->id) }}"
                                            class="btn btn-sm btn-secondary fw-bold">
                                            <i class="bi bi-people-fill me-1"></i> Lihat Pendaftar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Tidak ada kegiatan yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div>
                {{-- Informasi pagination opsional --}}
                Menampilkan {{ $activities->firstItem() }} sampai {{ $activities->lastItem() }} dari
                {{ $activities->total() }}
                Total Kegiatan
            </div>
            <div>
                {{ $activities->links() }}
            </div>
        </div>
    </div>

    {{-- =========================== --}}
    {{-- MODAL KONFIRMASI HAPUS --}}
    {{-- =========================== --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                {{-- Form Penghapusan --}}
                {{-- Action akan diisi otomatis oleh JavaScript --}}
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-semibold" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-semibold">Apakah Anda yakin?</h5>
                        <p class="text-muted">Data kegiatan ini akan dihapus permanen dan tidak dapat dikembalikan.</p>
                    </div>

                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger px-4 fw-semibold">
                            Ya, Hapus Kegiatan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- =========================== --}}
    {{-- JAVASCRIPT --}}
    {{-- =========================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Ambil semua tombol dengan class .btn-delete
            var deleteButtons = document.querySelectorAll('.btn-delete');

            // 2. Ambil elemen Modal dan Form
            var deleteModalElement = document.getElementById('deleteModal');
            var deleteForm = document.getElementById('deleteForm');

            // Inisialisasi Bootstrap Modal
            var deleteModal = new bootstrap.Modal(deleteModalElement);

            // 3. Loop setiap tombol untuk menambahkan event click
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil ID dari atribut data-id
                    var activityId = this.getAttribute('data-id');

                    // 4. Update URL Action pada Form
                    // Ganti 'schedules.destroy' sesuai nama route delete Anda di web.php
                    // Kita gunakan placeholder ':id' lalu mereplace-nya dengan ID asli
                    var url = "{{ route('schedules.delete', ':id') }}";
                    url = url.replace(':id', activityId);

                    deleteForm.setAttribute('action', url);

                    // 5. Tampilkan Modal
                    deleteModal.show();
                });
            });
        });
    </script>
    {{-- MODAL KONFIRMASI HAPUS & SCRIPT DELETE AJAX --}}
    {{-- @include('pengurus.partials.delete_modal') --}}

@endsection
