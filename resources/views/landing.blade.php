@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    .lp-hero { padding: 2.5rem 0 3rem; }
    .lp-hero h1 { font-size: clamp(1.9rem, 4.2vw, 2.75rem); line-height: 1.15; font-weight: 700; letter-spacing: -.01em; }
    .lp-lead { font-size: 1.125rem; color: var(--muted); max-width: 52ch; }

    .lp-stats { display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
    .lp-stat { padding: 1.25rem 0; }
    .lp-stat + .lp-stat { border-left: 1px solid var(--line); padding-left: 1.25rem; }
    .lp-stat-num { font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 700; line-height: 1.1; }
    .lp-stat-label { color: var(--muted); font-size: .9rem; }

    .lp-section { padding: 3rem 0; }
    .lp-section + .lp-section { border-top: 1px solid var(--line); }
    .lp-section h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: .25rem; }
    .lp-section .lp-sub { color: var(--muted); margin-bottom: 1.75rem; max-width: 60ch; }

    .lp-steps { list-style: none; padding: 0; margin: 0; display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); }
    .lp-steps li { border-top: 2px solid var(--accent); padding-top: .9rem; }
    .lp-step-no { font-weight: 700; color: var(--accent); }
    .lp-steps h3 { font-size: 1.05rem; font-weight: 600; margin: .15rem 0 .35rem; }
    .lp-steps p { color: var(--muted); margin: 0; font-size: .95rem; }

    .lp-cats { display: grid; gap: 0 2.5rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
    .lp-cat { border-top: 1px solid var(--line); padding: 1rem 0; }
    .lp-cat h3 { font-size: 1.05rem; font-weight: 600; margin-bottom: .2rem; }
    .lp-cat p { color: var(--muted); margin: 0; font-size: .95rem; }

    /* Card Before-After */
   .ba { 
        background: #fff; 
        border: 1px solid var(--line); 
        border-radius: 6px; 
        overflow: hidden; 
        height: 100%; 
        display: flex; 
        flex-direction: column; 
    }
    .ba-photos { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; background: var(--line); }
    .ba-photos img { width: 100%; aspect-ratio: 4 / 3; object-fit: cover; display: block; }
    .ba-cap { font-size: .85rem; color: var(--muted); padding: .35rem .75rem; }

    .ba-body { 
        padding: .9rem 1rem 1rem; 
        display: flex; 
        flex-direction: column; 
        flex: 1; /* Mengisi sisa ruang tinggi kartu */
    }

    /* Mendorong paragraf/elemen terakhir (bagian ulasan & rating) ke dasar kartu secara otomatis */
    .ba-body > p:last-child,
    .ba-body > div:last-child {
        margin-top: auto;
        padding-top: 1rem; /* Jarak aman dari deskripsi di atasnya */
    }

    .lp-empty { border: 1px dashed #c3cad2; border-radius: 6px; padding: 1.5rem; background: #fff; }
    .lp-caption { font-size: .9rem; color: var(--muted); margin: .6rem 0 0; }
</style>
@endpush

@section('content')
@php
    $featured = $completedReports->first();
    $others = $completedReports->skip(1);
    $fmt = fn ($n) => number_format($n, 0, ',', '.');
@endphp

<section class="lp-hero">
    <div class="row g-5 align-items-stretch">
        <div class="col-lg-6">
            <h1>Laporkan  Keluhan Anda Melalui Platform SIGAP!</h1>
            <p class="lp-lead mt-3">
                Maju Bersama SIGAP, Wadah Keluhan Masyarakat Yang  Efektif serta Inovative 
            </p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Buka dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Buat laporan</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Masuk</a>
                @endauth
            </div>
            @guest
                <p class="small text-muted mt-3 mb-0">
                    Untuk membuat laporan, daftar dulu dengan nama, email, dan password.
                </p>
            @endguest
        </div>  <!-- DITAMBAHKAN KEMBALI: Kolom kanan untuk menampilkan laporan selesai utama -->
</section>

<section class="lp-stats" aria-label="Ringkasan laporan">
    <div class="lp-stat">
        <div class="lp-stat-num">{{ $fmt($stats['total']) }}</div>
        <div class="lp-stat-label">Laporan masuk</div>
    </div>
    <div class="lp-stat">
        <div class="lp-stat-num">{{ $fmt($stats['completed']) }}</div>
        <div class="lp-stat-label">Sudah selesai</div>
    </div>
    <div class="lp-stat">
        <div class="lp-stat-num">{{ $stats['avg_rating'] > 0 ? number_format($stats['avg_rating'], 1, ',', '.') : '-' }}</div>
        <div class="lp-stat-label">Rata-rata nilai dari warga (skala 1 sampai 5)</div>
    </div>
</section>

<section class="lp-section">
    <h2>Cara kerjanya</h2>
    <p class="lp-sub">Dari laporan dikirim sampai masalah selesai, ada empat langkah.</p>
    <ol class="lp-steps">
        <li>
            <span class="lp-step-no">1</span>
            <h3>Anda mengirim laporan</h3>
            <p>Pilih kategori, tulis masalahnya, lalu tambahkan foto dan alamat.</p>
        </li>
        <li>
            <span class="lp-step-no">2</span>
            <h3>Admin memeriksa</h3>
            <p>Jika laporan ditolak, alasannya ditulis di halaman laporan Anda.</p>
        </li>
        <li>
            <span class="lp-step-no">3</span>
            <h3>Petugas mengerjakan</h3>
            <p>Petugas sesuai kategori mengambil laporan, lalu mengunggah foto hasil pekerjaan.</p>
        </li>
        <li>
            <span class="lp-step-no">4</span>
            <h3>Anda menilai hasilnya</h3>
            <p>Setelah selesai, beri nilai 1 sampai 5 dan tulis ulasan jika perlu.</p>
        </li>
    </ol>
</section>

<section class="lp-section">
    <h2>Yang bisa dilaporkan</h2>
    <p class="lp-sub">Pilih kategori yang paling sesuai agar laporan sampai ke petugas yang tepat.</p>
    <div class="lp-cats">
        @foreach ($categories as $category)
            <div class="lp-cat">
                <h3>{{ $category->name }}</h3>
                <p>{{ $category->description }}</p>
            </div>
        @endforeach
    </div>
</section>

@if ($stats['completed'] > 0)
    <section class="lp-section">
        <h2>Hasil pekerjaan</h2>
        <p class="lp-sub">Foto sebelum dan sesudah dari laporan warga yang sudah dikerjakan petugas</p>
        <div class="row g-4">
            @foreach ($completedReports as $report)
                <div class="col-md-6">
                    @include('partials.before-after', ['report' => $report])
                </div>
            @endforeach
        </div>
    </section>
@else
    <div class="lp-empty">
        <p class="fw-semibold mb-1">Belum ada laporan yang selesai.</p>
        <p class="text-muted mb-0">
            Hasil pekerjaan petugas akan tampil di sini, lengkap dengan foto sebelum dan sesudah.
        </p>
    </div>
@endif

@endsection