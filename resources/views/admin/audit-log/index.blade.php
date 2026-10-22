@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800 fw-bold mb-0">Log Aktivitas</h1>
    </div>

    {{-- Section Form Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.audit-log.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label for="start_date" class="form-label text-muted small fw-semibold">Dari tanggal</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>

                <div class="col-md-2">
                    <label for="end_date" class="form-label text-muted small fw-semibold">Sampai tanggal</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                <div class="col-md-2">
                    <label for="action" class="form-label text-muted small fw-semibold">Jenis aksi</label>
                    <select id="action" name="action" class="form-select text-truncate">
                        <option value="">Semua</option>
                        <option value="auth.login" {{ request('action') == 'auth.login' ? 'selected' : '' }}>Login</option>
                        <option value="auth.logout" {{ request('action') == 'auth.logout' ? 'selected' : '' }}>Logout</option>
                        <option value="auth.register" {{ request('action') == 'auth.register' ? 'selected' : '' }}>Register</option>
                        <option value="report.created" {{ request('action') == 'report.created' ? 'selected' : '' }}>Buat Laporan</option>
                        <option value="report.validated" {{ request('action') == 'report.validated' ? 'selected' : '' }}>Verifikasi Laporan</option>
                        <option value="report.claimed" {{ request('action') == 'report.claimed' ? 'selected' : '' }}>Proses Laporan</option>
                        <option value="report.completed" {{ request('action') == 'report.completed' ? 'selected' : '' }}>Selesaikan Laporan</option>
                        <option value="report.rated" {{ request('action') == 'report.rated' ? 'selected' : '' }}>Penilaian</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="role" class="form-label text-muted small fw-semibold">Peran</label>
                    <select id="role" name="role" class="form-select">
                        <option value="">Semua</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Warga</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="search" class="form-label text-muted small fw-semibold">Cari</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Nama / IP" value="{{ request('search') }}">
                </div>

                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary flex-fill fw-semibold">Filter</button>
                    @if(request()->hasAny(['start_date', 'end_date', 'action', 'role', 'search']))
                        <a href="{{ route('admin.audit-log.index') }}" class="btn btn-outline-secondary" title="Reset Filter">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Section Tabel Data Log --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 18%;">Waktu</th>
                            <th style="width: 22%;">Pelaku</th>
                            <th style="width: 20%;">Aksi</th>
                            <th style="width: 15%;">Objek</th>
                            <th style="width: 13%;">IP Address</th>
                            <th class="text-center pe-4" style="width: 12%;">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            {{-- Baris Utama --}}
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $log->created_at ? $log->created_at->translatedFormat('d M Y') : '—' }}</span>
                                    <br>
                                    <small class="text-muted">{{ $log->created_at ? $log->created_at->format('H:i:s') : '' }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $log->user_name ?? 'Sistem' }}</div>
                                    <small class="badge bg-secondary text-capitalize">{{ $log->user_role ?? 'System' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary px-2 py-1">
                                        {{ $log->action }}
                                    </span>
                                    <div class="small text-muted mt-1">{{ Str::limit($log->description, 40) }}</div>
                                </td>
                                <td>
                                    <span class="text-secondary fw-semibold">
                                        {{ $log->object_type ? class_basename($log->object_type) . ' #' . $log->object_id : '—' }}
                                    </span>
                                </td>
                                <td>
                                    <code class="text-muted">{{ $log->ip_address ?? '127.0.0.1' }}</code>
                                </td>
                                <td class="text-center pe-4">
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#detail-log-{{ $log->id }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>

                            {{-- Baris Detail Accordion --}}
                            <tr class="collapse bg-light" id="detail-log-{{ $log->id }}">
                                <td colspan="6" class="p-3 border-bottom">
                                    <div class="card card-body border-0 shadow-sm">
                                        <h6 class="fw-bold mb-2 text-primary">Rincian Perubahan Data</h6>
                                        
                                        @php
                                            $oldValues = is_array($log->old_values) ? $log->old_values : json_decode($log->old_values ?? '[]', true);
                                            $newValues = is_array($log->new_values) ? $log->new_values : json_decode($log->new_values ?? '[]', true);
                                            $allKeys = array_unique(array_merge(array_keys($oldValues ?? []), array_keys($newValues ?? [])));
                                        @endphp

                                        @if(count($allKeys) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered bg-white mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 30%;">Field</th>
                                                            <th style="width: 35%;">Sebelum</th>
                                                            <th style="width: 35%;">Sesudah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($allKeys as $key)
                                                            <tr>
                                                                <td class="fw-semibold text-secondary">{{ $key }}</td>
                                                                <td class="text-danger">{{ isset($oldValues[$key]) ? (is_array($oldValues[$key]) ? json_encode($oldValues[$key]) : $oldValues[$key]) : '—' }}</td>
                                                                <td class="text-success">{{ isset($newValues[$key]) ? (is_array($newValues[$key]) ? json_encode($newValues[$key]) : $newValues[$key]) : '—' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted mb-0 small">Tidak ada perubahan atribut data langsung.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <span>Tidak ada log aktivitas yang ditemukan.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if(isset($logs) && $logs->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-end">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection