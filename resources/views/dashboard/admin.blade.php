@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h4 class="fw-bold mb-3"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Dashboard Admin Kelurahan</h4>

{{-- KPI --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card stat-card text-center h-100"><div class="card-body">
            <div class="text-muted small">Total Laporan</div>
            <div class="h3 fw-bold mb-0">{{ $kpi['total'] }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card text-center h-100 border-start border-4 border-warning"><div class="card-body">
            <div class="text-muted small">Menunggu Verifikasi</div>
            <div class="h3 fw-bold mb-0 text-warning">{{ $kpi['pending'] }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card text-center h-100 border-start border-4 border-primary"><div class="card-body">
            <div class="text-muted small">Sedang Diproses</div>
            <div class="h3 fw-bold mb-0 text-primary">{{ $kpi['processing'] }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card text-center h-100 border-start border-4 border-success"><div class="card-body">
            <div class="text-muted small">Selesai</div>
            <div class="h3 fw-bold mb-0 text-success">{{ $kpi['completed'] }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card text-center h-100 border-start border-4 border-danger"><div class="card-body">
            <div class="text-muted small">Ditolak</div>
            <div class="h3 fw-bold mb-0 text-danger">{{ $kpi['rejected'] }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card text-center h-100"><div class="card-body">
            <div class="text-muted small">Rating Rata-rata</div>
            <div class="h3 fw-bold mb-0 text-warning">
                <i class="fa-solid fa-star"></i> {{ $kpi['avg_rating'] > 0 ? number_format($kpi['avg_rating'], 1) : '-' }}
            </div>
        </div></div>
    </div>
</div>

{{-- Antrean Verifikasi --}}
<div class="card stat-card mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="fa-solid fa-inbox me-1 text-warning"></i>Antrean Verifikasi
        <span class="badge text-bg-warning ms-1">{{ $pendingReports->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Foto</th>
                    <th>Laporan</th>
                    <th>Pelapor</th>
                    <th>Lokasi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingReports as $report)
                    <tr>
                        <td>
                            <a href="{{ $report->photo_before_url }}" target="_blank" rel="noopener">
                                <img src="{{ $report->photo_before_url }}" class="report-thumb" alt="Foto">
                            </a>
                        </td>
                        <td style="max-width: 320px;">
                            <div class="fw-semibold">{{ $report->title }}</div>
                            <span class="badge text-bg-secondary">{{ $report->category->name }}</span>
                            <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($report->description, 110) }}</div>
                            <div class="small text-muted">{{ $report->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td>
                            {{ $report->user->name }}
                            @if ($report->user->phone)
                                <div class="small text-muted">{{ $report->user->phone }}</div>
                            @endif
                        </td>
                        <td>
                            {{ $report->location }}
                            @if ($report->latitude && $report->longitude)
                                <div>
                                    <a class="small" target="_blank" rel="noopener"
                                       href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}">
                                        <i class="fa-solid fa-map-location-dot me-1"></i>Lihat peta
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            @if (in_array($report->category_id, $coveredCategoryIds, true))
                                <button type="button" class="btn btn-sm btn-success"
                                        data-bs-toggle="modal" data-bs-target="#acceptModal"
                                        data-url="{{ route('reports.verify', $report) }}"
                                        data-title="{{ $report->title }}"
                                        data-category="{{ $report->category->name }}">
                                    <i class="fa-solid fa-check me-1"></i>Terima
                                </button>
                            @else
                                <span class="d-inline-block" tabindex="0" title="Belum ada petugas untuk kategori ini">
                                    <button type="button" class="btn btn-sm btn-success" disabled>
                                        <i class="fa-solid fa-check me-1"></i>Terima
                                    </button>
                                </span>
                            @endif
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#rejectModal"
                                    data-url="{{ route('reports.verify', $report) }}"
                                    data-title="{{ $report->title }}">
                                <i class="fa-solid fa-xmark me-1"></i>Tolak
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fa-solid fa-circle-check fa-2x text-success d-block mb-2"></i>
                            Tidak ada laporan yang menunggu verifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($pendingReports->hasPages())
        <div class="card-footer bg-white">
            {{ $pendingReports->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<div class="row g-4">
    {{-- Rekap Kategori --}}
    <div class="col-lg-7">
        <div class="card stat-card h-100">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-table me-1 text-primary"></i>Rekapitulasi Kinerja per Kategori
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kategori</th>
                            <th class="text-center">Petugas</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Selesai</th>
                            <th class="text-center">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categoryRecap as $row)
                            <tr>
                                <td>{{ $row->name }}</td>
                                <td class="text-center">
                                    @if ($row->petugas_count > 0)
                                        {{ $row->petugas_count }}
                                    @else
                                        <span class="badge text-bg-danger">Belum ada</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $row->reports_count }}</td>
                                <td class="text-center">{{ $row->completed_count }}</td>
                                <td class="text-center">
                                    @if ($row->reports_avg_rating)
                                        <i class="fa-solid fa-star text-warning"></i> {{ number_format($row->reports_avg_rating, 1) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Ulasan Terbaru --}}
    <div class="col-lg-5">
        <div class="card stat-card h-100">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-comments me-1 text-warning"></i>Ulasan Kepuasan Terbaru
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($latestReviews as $item)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong class="small">{{ $item->title }}</strong>
                            <span class="small">@include('partials.stars', ['value' => $item->rating])</span>
                        </div>
                        <div class="small text-muted">{{ $item->user->name }} &middot; {{ $item->category->name }}</div>
                        @if ($item->review)
                            <div class="small fst-italic mt-1">"{{ \Illuminate\Support\Str::limit($item->review, 120) }}"</div>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">Belum ada ulasan.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

{{-- Modal Terima --}}
<div class="modal fade js-action-modal" id="acceptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="validate">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-check text-success me-1"></i>Setujui Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Setujui laporan <strong class="js-title"></strong>?
                Laporan akan masuk ke antrean petugas kategori
                <span class="badge text-bg-secondary js-category"></span>
                (status <span class="badge text-bg-info">VALIDATED</span>) dan menunggu diambil oleh salah satu petugas.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Ya, Setujui</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade js-action-modal" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="reject">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-xmark text-danger me-1"></i>Tolak Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tolak laporan <strong class="js-title"></strong>?</p>
                <label for="rejection_note" class="form-label">Alasan penolakan <span class="text-danger">*</span></label>
                <textarea id="rejection_note" name="rejection_note" rows="3" class="form-control" maxlength="1000"
                          placeholder="Contoh: Laporan duplikat / di luar wilayah kelurahan" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Tolak Laporan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.js-action-modal').forEach(function (modal) {
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            modal.querySelector('form').action = button.dataset.url;
            modal.querySelector('.js-title').textContent = button.dataset.title;
            const category = modal.querySelector('.js-category');
            if (category) {
                category.textContent = button.dataset.category || '';
            }
        });
    });
</script>
@endpush
