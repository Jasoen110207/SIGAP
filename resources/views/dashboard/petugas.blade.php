@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
@if (! $category)
    <div class="alert alert-warning">
        <i class="fa-solid fa-triangle-exclamation me-1"></i>
        Akun Anda belum memiliki kategori pekerjaan. Hubungi admin kelurahan untuk pengaturan akses.
    </div>
@else
    <div class="mb-3">
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-helmet-safety me-2 text-primary"></i>Dashboard Petugas Lapangan</h4>
        <span class="badge text-bg-primary"><i class="fa-solid fa-tag me-1"></i>{{ $category->name }}</span>
        <small class="text-muted ms-2">Selesai hari ini: <strong>{{ $completedToday }}</strong> laporan</small>
    </div>

    {{-- Tugas Saya --}}
    <h5 class="fw-bold mt-4 mb-3">
        <i class="fa-solid fa-person-digging text-primary me-1"></i>Tugas Saya
        <span class="badge text-bg-primary">{{ $myReports->count() }}</span>
    </h5>
    <div class="row g-4 mb-5">
        @forelse ($myReports as $report)
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card h-100 border-top border-4 border-primary">
                    <img src="{{ $report->photo_before_url }}" class="photo-cover" alt="Foto laporan">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $report->title }}</h6>
                        <p class="small text-muted mb-1"><i class="fa-solid fa-location-dot me-1"></i>{{ $report->location }}</p>
                        @if ($report->latitude && $report->longitude)
                            <a class="small" target="_blank" rel="noopener"
                               href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}">
                                <i class="fa-solid fa-map-location-dot me-1"></i>Lihat peta
                            </a>
                        @endif
                        <p class="small mt-2 mb-1">{{ \Illuminate\Support\Str::limit($report->description, 140) }}</p>
                        <p class="small text-muted mb-0">Pelapor: {{ $report->user->name }} &middot; {{ $report->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <button type="button" class="btn btn-success flex-grow-1"
                                data-bs-toggle="modal" data-bs-target="#completeModal"
                                data-url="{{ route('reports.complete', $report) }}"
                                data-title="{{ $report->title }}">
                            <i class="fa-solid fa-flag-checkered me-1"></i>Selesaikan
                        </button>
                        <form action="{{ route('reports.release', $report) }}" method="POST"
                              onsubmit="return confirm('Kembalikan laporan ini ke antrean?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-secondary" title="Lepas tugas">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0 text-center">Anda belum mengambil tugas. Ambil dari antrean di bawah.</div>
            </div>
        @endforelse
    </div>

    {{-- Antrean Kategori --}}
    <h5 class="fw-bold mb-3">
        <i class="fa-solid fa-inbox text-info me-1"></i>Antrean Kategori
        <span class="badge text-bg-info">{{ $queueReports->count() }}</span>
    </h5>
    <div class="row g-4 mb-5">
        @forelse ($queueReports as $report)
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card h-100 border-top border-4 border-info">
                    <img src="{{ $report->photo_before_url }}" class="photo-cover" alt="Foto laporan">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $report->title }}</h6>
                        <p class="small text-muted mb-1"><i class="fa-solid fa-location-dot me-1"></i>{{ $report->location }}</p>
                        <p class="small mb-1">{{ \Illuminate\Support\Str::limit($report->description, 140) }}</p>
                        <p class="small text-muted mb-0">Pelapor: {{ $report->user->name }} &middot; {{ $report->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="card-footer bg-white">
                        <form action="{{ route('reports.start', $report) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-hand me-1"></i>Ambil Tugas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0 text-center">Antrean kosong. Belum ada laporan baru untuk kategori ini.</div>
            </div>
        @endforelse
    </div>

    {{-- Dikerjakan Rekan --}}
    @if ($teamReports->isNotEmpty())
        <h5 class="fw-bold mb-3">
            <i class="fa-solid fa-people-group text-secondary me-1"></i>Sedang Dikerjakan Rekan
            <span class="badge text-bg-secondary">{{ $teamReports->count() }}</span>
        </h5>
        <div class="card stat-card mb-5">
            <ul class="list-group list-group-flush">
                @foreach ($teamReports as $report)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $report->title }}</div>
                            <div class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ $report->location }}</div>
                        </div>
                        <span class="badge text-bg-secondary">
                            <i class="fa-solid fa-lock me-1"></i>{{ $report->petugas->name ?? 'Petugas belum tercatat' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Riwayat Selesai --}}
    <h5 class="fw-bold mb-3">
        <i class="fa-solid fa-circle-check text-success me-1"></i>Riwayat Selesai
        <span class="badge text-bg-success">{{ $completedReports->total() }}</span>
    </h5>
    <div class="row g-4">
        @forelse ($completedReports as $report)
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card h-100 border-top border-4 border-success">
                    <div class="row g-0">
                        <div class="col-6 position-relative">
                            <span class="badge text-bg-danger label-chip">SEBELUM</span>
                            <img src="{{ $report->photo_before_url }}" class="photo-compare" alt="Foto sebelum">
                        </div>
                        <div class="col-6 position-relative">
                            <span class="badge text-bg-success label-chip">SESUDAH</span>
                            <img src="{{ $report->photo_after_url }}" class="photo-compare" alt="Foto sesudah">
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $report->title }}</h6>
                        <p class="small text-muted mb-1"><i class="fa-solid fa-location-dot me-1"></i>{{ $report->location }}</p>
                        <p class="small mb-1"><strong>Eksekutor:</strong> {{ $report->executor_name }}</p>
                        <p class="small mb-2">{{ \Illuminate\Support\Str::limit($report->executor_note, 100) }}</p>
                        <div class="small text-muted">
                            Selesai {{ $report->updated_at->format('d M Y H:i') }}
                            @if ($report->petugas)
                                &middot; oleh {{ $report->petugas->name }}
                            @endif
                        </div>
                        @if ($report->rating)
                            <div class="mt-1">@include('partials.stars', ['value' => $report->rating])</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0 text-center">Belum ada laporan yang diselesaikan.</div>
            </div>
        @endforelse
    </div>

    @if ($completedReports->hasPages())
        <div class="mt-4">
            {{ $completedReports->links('pagination::bootstrap-5') }}
        </div>
    @endif

    {{-- Modal Upload Bukti Selesai --}}
    <div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-flag-checkered text-success me-1"></i>Selesaikan Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Laporan: <strong class="js-title"></strong></p>
                    <div class="mb-3">
                        <label for="photo_after" class="form-label">Foto Bukti Selesai <span class="text-danger">*</span></label>
                        <input type="file" id="photo_after" name="photo_after" accept="image/*" class="form-control" required>
                        <div class="form-text">JPG/PNG/WEBP, maksimal 4 MB.</div>
                    </div>
                    <div class="mb-3">
                        <label for="executor_name" class="form-label">Nama Eksekutor <span class="text-danger">*</span></label>
                        <input type="text" id="executor_name" name="executor_name" class="form-control" maxlength="100"
                               placeholder="Contoh: Budi - Tim Drainase" required>
                    </div>
                    <div class="mb-3">
                        <label for="executor_note" class="form-label">Catatan Perbaikan <span class="text-danger">*</span></label>
                        <textarea id="executor_note" name="executor_note" rows="3" class="form-control" maxlength="2000"
                                  placeholder="Jelaskan tindakan perbaikan yang dilakukan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-upload me-1"></i>Tandai Selesai</button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    const completeModal = document.getElementById('completeModal');
    if (completeModal) {
        completeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            completeModal.querySelector('form').action = button.dataset.url;
            completeModal.querySelector('.js-title').textContent = button.dataset.title;
        });
    }
</script>
@endpush
