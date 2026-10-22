@extends('layouts.app')

@section('title', 'Kelola Petugas')

@section('content')
<div class="container-fluid py-3">
    {{-- Header Section --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h4 class="fw-bold mb-0 text-dark">
            <i class="fa-solid fa-users-gear me-2 text-primary"></i>Kelola Petugas Lapangan
        </h4>
        <a href="{{ route('admin.petugas.create') }}" class="btn btn-primary fw-semibold shadow-sm">
            <i class="fa-solid fa-user-plus me-1"></i>Tambah Petugas
        </a>
    </div>

    {{-- Main Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Kategori</th>
                            <th class="text-center">Dipegang</th>
                            <th class="text-center">Selesai</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($petugasList as $petugas)
                            @php
                                $confirmMessage = $petugas->is_active 
                                    ? 'Nonaktifkan akun ini? Laporan yang sedang dipegang akan dikembalikan ke antrean.' 
                                    : 'Aktifkan kembali akun ini?';
                            @endphp
                            <tr class="{{ $petugas->is_active ? '' : 'table-secondary opacity-75' }}">
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $petugas->name }}</div>
                                    <div class="small text-muted">{{ $petugas->email }}</div>
                                    @if ($petugas->phone)
                                        <div class="small text-muted">
                                            <i class="fa-solid fa-phone me-1"></i>{{ $petugas->phone }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($petugas->category)
                                        <span class="badge text-bg-secondary fw-normal px-2 py-1">
                                            {{ $petugas->category->name }}
                                        </span>
                                    @else
                                        <span class="badge text-bg-danger fw-normal px-2 py-1">Belum ada kategori</span>
                                    @endif
                                </td>
                                <td class="text-center fw-semibold text-primary">
                                    {{ $petugas->active_count ?? 0 }}
                                </td>
                                <td class="text-center fw-semibold text-success">
                                    {{ $petugas->done_count ?? 0 }}
                                </td>
                                <td>
                                    @if ($petugas->is_active)
                                        <span class="badge text-bg-success px-2 py-1">Aktif</span>
                                    @else
                                        <span class="badge text-bg-dark px-2 py-1">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="{{ route('admin.petugas.edit', $petugas) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fa-solid fa-pen me-1"></i>Edit
                                    </a>

                                    <form action="{{ route('admin.petugas.toggle', $petugas) }}" 
                                          method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('{{ $confirmMessage }}');">
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
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-user-xmark display-6 d-block mb-2 text-secondary"></i>
                                    Belum ada data petugas. Klik <strong>"Tambah Petugas"</strong> untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Footer --}}
        @if ($petugasList->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-end">
                {{ $petugasList->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection