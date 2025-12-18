@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #952638;">
                <h5 class="mb-0 fw-semibold">Daftar Warga Terdaftar</h5>
                <span class="badge bg-secondary">Total: {{ $warga->total() }} Warga</span>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Warga</th>
                                <th>Kontak</th>
                                <th>Status Akun</th>
                                <th>Total Kegiatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warga as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- Foto Profil Kecil --}}
                                            <img src="{{ $item->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . $item->nama_lengkap }}"
                                                class="rounded-circle me-2" width="40" height="40">
                                            <div>
                                                <div class="fw-semibold">{{ $item->nama_lengkap }}</div>
                                                <small class="text-muted">NIK: {{ $item->nik ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">{{ $item->email }}</div>
                                        <div class="small">{{ $item->nomor_telepon ?? '-' }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->is_active)
                                            <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">{{ $item->registrations->count() }}
                                            Kegiatan</span>
                                    </td>
                                    <td class="text-end">
                                        {{-- TOMBOL LIHAT HISTORY (MODAL) --}}
                                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal"
                                            data-bs-target="#historyModal{{ $item->id }}">
                                            <i class="bi bi-clock-history"></i> Riwayat
                                        </button>

                                        {{-- TOMBOL BLOCK/UNBLOCK --}}
                                        <form action="{{ route('kelola.warga.toggle', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')    
                                            @if ($item->is_active)
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Yakin ingin memblokir warga ini?')">
                                                    <i class="bi bi-person-x-fill"></i> Non-Aktifkan
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Aktifkan kembali warga ini?')">
                                                    <i class="bi bi-person-check-fill"></i> Aktifkan
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>

                                {{-- MODAL HISTORY PER USER --}}
                                <div class="modal fade" id="historyModal{{ $item->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Riwayat Kegiatan:
                                                    <strong>{{ $item->name }}</strong></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($item->registrations->isEmpty())
                                                    <p class="text-center text-muted my-3">Belum pernah mendaftar kegiatan
                                                        apapun.</p>
                                                @else
                                                    <ul class="list-group list-group-flush">
                                                        @foreach ($item->registrations as $reg)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    {{-- Asumsi relasi ke activity/schedule bernama 'activity' --}}
                                                                    <div class="fw-semibold">
                                                                        {{ $reg->activity->title ?? 'Kegiatan Dihapus' }}
                                                                    </div>
                                                                    <small class="text-muted">
                                                                        {{ $reg->activity->start}}
                                                                    </small>
                                                                </div>
                                                                <span class="badge bg-primary rounded-pill">Terdaftar</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- END MODAL --}}

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada warga yang mendaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $warga->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
