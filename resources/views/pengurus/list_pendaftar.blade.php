@extends('layouts.admin')

@section('title', 'Pendaftar Kegiatan: ' . $activity->title)

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #343A40;">
                    <i class="bi bi-people-fill me-2"></i> Pendaftar: {{ $activity->title }}
                </h2>
                <p class="text-muted small">
                    Tanggal: {{ $activity->start->translatedFormat('d F Y') }} | Total Pendaftar:
                    {{ $registrations->total() }}
                </p>
            </div>
            <a href="{{ route('kelola.kegiatan') }}" class="btn btn-secondary fw-bold">
                <i class="bi bi-arrow-left"></i> Kembali ke Kelola Kegiatan
            </a>
        </div>

        {{-- Area Search dan Filter --}}
        <div class="card mb-4 shadow-sm border-2" style="border-color: #952638;">
            <div class="card-body">
                {{-- Form Search (Target: mencari di kolom User) --}}
                <form action="{{ route('participation.show', $activity->id) }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="Cari Nama, NIK, atau Nomor HP Pendaftar..." value="{{ request('search') }}">
                    <button type="submit" class="btn text-white fw-bold" style="background-color:#952638;">
                        <i class="bi bi-search"></i>
                    </button>
                    @if (request('search'))
                        <a href="{{ route('participation.show', $activity->id) }}"
                            class="btn btn-outline-secondary ms-2">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabel Pendaftar --}}
        <div class="card shadow-sm border-2" style="border-color: #952638;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                {{-- <th scope="col">NIK</th> --}}
                                <th scope="col">No. HP</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Usia</th>
                                <th scope="col">Jenis Kelamin</th>
                                {{-- <th scope="col">Pekerjaan</th> --}}
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @forelse ($registrations as $registration)
                                @php
                                    $user = $registration->user; // Objek User yang mendaftar
                                @endphp
                                <tr class="text-center">
                                    <td>{{ $registrations->firstItem() + $loop->index }}</td>
                                    <td>{{ $user->nama_lengkap }}</td>
                                    {{-- <td>{{ $user->nik }}</td> --}}
                                    <td>{{ $user->nomor_telepon }}</td>
                                    <td>{{ $user->alamat ?? 'Belum Diisi' }}</td>
                                    <td>
                                        {{ $user->usia ? $user->usia . ' Tahun' : 'Tidak Diketahui' }}
                                    </td>
                                    <td >
                                        {{ $user->jenis_kelamin ?? 'Tidak Diketahui' }}
                                    </td>
                                    {{-- <td >{{ $user->pekerjaan ?? 'Belum Diisi' }}</td> --}}
                                    <td>
                                        <span
                                            class="badge 
                                        @if ($registration->status == 'registered') bg-success 
                                        @elseif($registration->status == 'attended') bg-primary 
                                        @else bg-warning text-dark @endif">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        Belum ada warga yang mendaftar untuk kegiatan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- PAGINATION INFORMASI --}}
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div>
                {{-- Informasi pagination opsional --}}
                Menampilkan {{ $registrations->firstItem() }} sampai {{ $registrations->lastItem() }} dari
                {{ $registrations->total() }}
                Total Pendaftar
            </div>
            <div>
                {{ $registrations->links() }}
            </div>
        </div>

    </div>

    <div class="container-fluid py-4">

        {{-- Cek apakah status sudah Selesai dan ada dokumentasi yang terlampir --}}
        @if ($activity->status == 'Selesai')
            <div class="mt-5">
                <h3 class="fw-bold mb-4 border-bottom pb-2" style="color: #198754;">
                    <i class="bi bi-images me-2"></i> Arsip Dokumentasi Kegiatan
                </h3>

                @if ($activity->documentation->count() > 0)

                    {{-- Gunakan row-cols-md-3 untuk 3 foto per baris --}}
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach ($activity->documentation as $doc)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0 overflow-hidden">

                                    {{-- FOTO DOKUMENTASI --}}
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative"
                                        style="height: 250px; overflow: hidden;">

                                        {{-- LOGIKA GAMBAR --}}
                                        {{-- object-fit: contain = Pastikan seluruh gambar masuk area (tidak terpotong) --}}
                                        <img src="{{ $doc->photo_url }}" class="img-fluid" alt="{{ $doc->caption }}"
                                            style="max-height: 100%; max-width: 100%; object-fit: contain;">

                                    </div>

                                    <div class="card-body">
                                        {{-- CAPTION / KETERANGAN --}}
                                        <p class="card-text small">
                                            {{ $doc->caption ?? 'Tanpa Keterangan Foto.' }}
                                        </p>
                                        <p class="small text-muted mb-0">
                                            Diunggah oleh: {{ $doc->uploader->nama_lengkap ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="card-footer text-muted small">
                                        Diunggah pada: {{ $doc->created_at->translatedFormat('d F Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div>
                            Menampilkan {{ $documentations->firstItem() }} sampai {{ $documentations->lastItem() }} dari
                            {{ $documentations->total() }} Dokumentasi
                        </div>
                        <div>
                            {{-- appends penting agar saat klik halaman dokumentasi, search pendaftar tidak reset --}}
                            {{ $documentations->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Kegiatan ini sudah Selesai, namun belum ada foto dokumentasi yang diunggah.
                    </div>
                @endif
            </div>
        @endif
    </div>
    @endsection
