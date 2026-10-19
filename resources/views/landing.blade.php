@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<section class="hero rounded-4 p-4 p-md-5 mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold">Suara Warga, Aksi Nyata.</h1>
            <p class="lead mb-4">
                SIGAP memudahkan warga melaporkan masalah lingkungan kelurahan — dari jalan rusak, lampu mati,
                hingga saluran air tersumbat — dan memantau penanganannya secara transparan.
            </p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg me-2">
                    <i class="fa-solid fa-gauge me-1"></i>Buka Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg me-2">
                    <i class="fa-solid fa-pen-to-square me-1"></i>Buat Laporan
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Masuk</a>
            @endauth
        </div>
        <div class="col-lg-4 d-none d-lg-block text-center">
            <i class="fa-solid fa-city" style="font-size: 9rem; opacity: .25;"></i>
        </div>
    </div>
</section>

<section class="row g-3 mb-5">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100 text-center">
            <div class="card-body">
                <i class="fa-solid fa-clipboard-list fa-2x text-primary mb-2"></i>
                <div class="h2 fw-bold mb-0">{{ number_format($stats['total']) }}</div>
                <div class="text-muted small">Total Laporan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100 text-center">
            <div class="card-body">
                <i class="fa-solid fa-circle-check fa-2x text-success mb-2"></i>
                <div class="h2 fw-bold mb-0">{{ number_format($stats['completed']) }}</div>
                <div class="text-muted small">Laporan Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100 text-center">
            <div class="card-body">
                <i class="fa-solid fa-person-digging fa-2x text-info mb-2"></i>
                <div class="h2 fw-bold mb-0">{{ number_format($stats['in_progress']) }}</div>
                <div class="text-muted small">Sedang Ditangani</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100 text-center">
            <div class="card-body">
                <i class="fa-solid fa-star fa-2x text-warning mb-2"></i>
                <div class="h2 fw-bold mb-0">{{ $stats['avg_rating'] > 0 ? number_format($stats['avg_rating'], 1) : '-' }}</div>
                <div class="text-muted small">Rata-rata Kepuasan</div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <h4 class="fw-bold mb-3">Kategori Laporan</h4>
    <div class="row g-3">
        @foreach ($categories as $category)
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $category->name }}</h6>
                        <p class="small text-muted mb-0">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section>
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h4 class="fw-bold mb-0">Hasil Nyata: Sebelum &amp; Sesudah</h4>
            <small class="text-muted">Laporan yang telah diselesaikan petugas lapangan</small>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($completedReports as $report)
            <div class="col-md-6 col-lg-4">
                <div class="card stat-card h-100">
                    <div class="row g-0">
                        <div class="col-6 position-relative">
                            <span class="badge text-bg-danger label-chip">SEBELUM</span>
                            <img src="{{ $report->photo_before_url }}" class="photo-compare rounded-top-start" alt="Foto sebelum">
                        </div>
                        <div class="col-6 position-relative">
                            <span class="badge text-bg-success label-chip">SESUDAH</span>
                            <img src="{{ $report->photo_after_url }}" class="photo-compare rounded-top-end" alt="Foto sesudah">
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="badge text-bg-secondary mb-2">{{ $report->category->name }}</span>
                        <h6 class="fw-bold">{{ $report->title }}</h6>
                        <p class="small text-muted mb-2">
                            <i class="fa-solid fa-location-dot me-1"></i>{{ $report->location }}
                        </p>
                        @if ($report->executor_note)
                            <p class="small mb-2">{{ \Illuminate\Support\Str::limit($report->executor_note, 100) }}</p>
                        @endif
                        <div class="small text-muted">
                            Dikerjakan oleh <strong>{{ $report->executor_name }}</strong>
                        </div>
                        @if ($report->rating)
                            <div class="mt-2">@include('partials.stars', ['value' => $report->rating])</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center mb-0">
                    <i class="fa-regular fa-folder-open me-1"></i>Belum ada laporan yang selesai ditampilkan.
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
