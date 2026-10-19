@extends('layouts.app')

@section('title', 'Kelola Petugas')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h4 class="fw-bold mb-0"><i class="fa-solid fa-users-gear me-2 text-primary"></i>Kelola Petugas Lapangan</h4>
    <a href="{{ route('petugas.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-user-plus me-1"></i>Tambah Petugas
    </a>
</div>

<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="text-center">Dipegang</th>
                    <th class="text-center">Selesai</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($petugasList as $petugas)
                    <tr class="{{ $petugas->is_active ? '' : 'table-secondary' }}">
                        <td>
                            <div class="fw-semibold">{{ $petugas->name }}</div>
                            <div class="small text-muted">{{ $petugas->email }}</div>
                            @if ($petugas->phone)
                                <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $petugas->phone }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($petugas->category)
                                <span class="badge text-bg-secondary">{{ $petugas->category->name }}</span>
                            @else
                                <span class="badge text-bg-danger">Belum ada kategori</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $petugas->active_count }}</td>
                        <td class="text-center">{{ $petugas->done_count }}</td>
                        <td>
                            @if ($petugas->is_active)
                                <span class="badge text-bg-success">Aktif</span>
                            @else
                                <span class="badge text-bg-dark">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('petugas.edit', $petugas) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen me-1"></i>Edit
                            </a>
                            <form action="{{ route('petugas.toggle', $petugas) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('{{ $petugas->is_active
                                      ? 'Nonaktifkan akun ini? Laporan yang sedang dipegang akan dikembalikan ke antrean.'
                                      : 'Aktifkan kembali akun ini?' }}');">
                                @csrf
                                @method('PATCH')
                                @if ($petugas->is_active)
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-user-slash me-1"></i>Nonaktifkan
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fa-solid fa-user-check me-1"></i>Aktifkan
                                    </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Belum ada petugas. Klik "Tambah Petugas".</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($petugasList->hasPages())
        <div class="card-footer bg-white">
            {{ $petugasList->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
