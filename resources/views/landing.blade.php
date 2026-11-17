@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    :root {
        --hero-overlay: rgba(11, 27, 34, 0.42);
        --hero-shadow: rgba(7, 18, 24, 0.28);
        --navy-900: #1E3A5F;
        --navy-800: #162C49;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-600: #475569;
        --slate-700: #334155;
        --amber-500: #f59e0b;
        --amber-600: #d97706;
    }

    .lp-hero {
        position: relative;
        width: 100vw;
        height: 100svh;
        min-height: 620px;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        overflow: hidden;
        background: #0f172a;
        border-radius: 0;
        box-shadow: none;
        isolation: isolate;
    }

    .lp-hero-slider {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .lp-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        visibility: hidden;
        transition: opacity .6s ease, visibility .6s ease;
    }

    .lp-slide.active {
        opacity: 1;
        visibility: visible;
    }

    .lp-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        filter: saturate(1) contrast(1.05);
    }

    .lp-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.46), rgba(15, 23, 42, 0.58)),
                    linear-gradient(90deg, rgba(30, 58, 95, 0.62), rgba(30, 58, 95, 0.18));
        z-index: 1;
    }

    .lp-hero-content {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 6rem 1.5rem 5rem;
    }

    .lp-hero-inner {
        max-width: 880px;
    }

    .lp-hero h1 {
        color: #fff;
        font-size: clamp(2.2rem, 4vw, 4.2rem);
        line-height: 1.08;
        font-weight: 800;
        letter-spacing: -.03em;
        margin: 0;
        text-shadow: 0 2px 20px rgba(0,0,0,.25);
    }

    .lp-lead {
        font-size: clamp(1.02rem, 1.4vw, 1.35rem);
        line-height: 1.6;
        color: rgba(255,255,255,.9);
        margin: 1rem auto 0;
        max-width: 60ch;
        text-shadow: 0 2px 10px rgba(0,0,0,.2);
    }

    .lp-hero-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: .8rem;
        margin-top: 1.6rem;
    }

    .lp-hero .btn-primary,
    .lp-hero .btn-outline-light {
        min-width: 148px;
        font-weight: 700;
        font-size: 1rem;
        padding: .9rem 1.5rem;
        border-radius: 999px;
        transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .lp-hero .btn-primary {
        background: var(--navy-900);
        border-color: var(--navy-900);
        color: #fff;
        box-shadow: 0 12px 24px rgba(30, 58, 95, 0.18);
    }

    .lp-hero .btn-primary:hover,
    .lp-hero .btn-primary:focus-visible {
        transform: translateY(-1px);
        background: var(--navy-800);
        border-color: var(--navy-800);
        color: #fff;
    }

    .lp-hero .btn-outline-light {
        color: #fff;
        border-color: rgba(255,255,255,.9);
        background: rgba(255,255,255,.05);
    }

    .lp-hero .btn-outline-light:hover,
    .lp-hero .btn-outline-light:focus-visible {
        background: rgba(255,255,255,.12);
        border-color: rgba(255,255,255,.95);
        color: #fff;
        transform: translateY(-1px);
    }

    .lp-hero-note {
        color: rgba(255,255,255,.82);
        font-size: .86rem;
        margin-top: 1rem;
    }

    .lp-slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.28);
        background: rgba(255,255,255,.08);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        transition: background-color .2s ease, transform .2s ease, border-color .2s ease;
    }

    .lp-slider-btn:hover,
    .lp-slider-btn:focus-visible {
        background: rgba(255,255,255,.16);
        border-color: rgba(255,255,255,.7);
        transform: translateY(-50%) scale(1.04);
        outline: none;
    }

    .lp-slider-btn.prev { left: 1.2rem; }
    .lp-slider-btn.next { right: 1.2rem; }

    .lp-slider-pagination {
        position: absolute;
        left: 50%;
        bottom: 1.6rem;
        transform: translateX(-50%);
        z-index: 3;
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .lp-dot {
        width: .72rem;
        height: .72rem;
        border: 0;
        border-radius: 999px;
        background: rgba(255,255,255,.45);
        transition: width .2s ease, background-color .2s ease, transform .2s ease;
        padding: 0;
    }

    .lp-dot.active {
        width: 2rem;
        background: rgba(255,255,255,1);
    }

    .lp-dot:hover,
    .lp-dot:focus-visible {
        background: rgba(255,255,255,.85);
        outline: none;
    }

    @media (max-width: 767px) {
        .lp-hero {
            min-height: 560px;
        }

        .lp-slider-btn {
            width: 2.5rem;
            height: 2.5rem;
        }

        .lp-slider-btn.prev { left: .8rem; }
        .lp-slider-btn.next { right: .8rem; }

        .lp-hero-content {
            padding-bottom: 7rem;
        }

        .lp-slider-pagination {
            bottom: 1rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .lp-slide,
        .lp-slider-btn,
        .lp-dot,
        .lp-hero .btn-primary,
        .lp-hero .btn-outline-light {
            transition: none !important;
        }
    }

    .lp-section {
        padding: 5rem 0;
        background: #fff;
    }

    .lp-section + .lp-section {
        border-top: 0;
    }

    .lp-scope-section {
        background: #f8fafc;
        border-top: 0;
        border-radius: 0;
        padding: 5rem 1.5rem;
        margin: 0 auto 0;
        box-shadow: none;
    }

    .section-intro {
        margin-bottom: 2.5rem;
    }

    .section-kicker {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .38rem .8rem;
        border-radius: 999px;
        background: rgba(30, 58, 95, 0.08);
        color: var(--navy-900);
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .14em;
        text-transform: uppercase;
        margin-bottom: .9rem;
        border: 1px solid rgba(30, 58, 95, 0.12);
    }

    .section-intro h2 {
        font-size: clamp(2.1rem, 3vw, 2.8rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -.04em;
        margin: 0;
        color: #0f172a;
    }

    .lp-section .lp-sub {
        color: var(--slate-600);
        margin: .85rem 0 0;
        max-width: 60ch;
        font-size: 1rem;
        line-height: 1.75;
    }

    .procedure-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.5rem;
        position: relative;
    }

    .procedure-card {
        position: relative;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.25rem;
        padding: 1.5rem 1.25rem;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.03);
        height: 100%;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .procedure-card:hover,
    .scope-card:hover,
    .visi-misi-card:hover,
    .ba:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 18px rgba(15, 23, 42, 0.06);
    }

    @media (min-width: 992px) {
        .procedure-card:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 2.15rem;
            right: -0.9rem;
            width: 1.5rem;
            height: 1px;
            background: rgba(30, 58, 95, 0.18);
        }
    }

    .procedure-step {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .procedure-step-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.6rem;
        height: 2.6rem;
        border-radius: 999px;
        background: var(--navy-900);
        color: #fff;
        font-size: .92rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .procedure-card h3 {
        font-size: 1.08rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .45rem;
        line-height: 1.5;
    }

    .procedure-card p {
        margin: 0;
        color: var(--slate-600);
        font-size: .95rem;
        line-height: 1.7;
    }

    .scope-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
    }

    .scope-card {
        display: flex;
        flex-direction: column;
        gap: .95rem;
        height: 100%;
        padding: 1.5rem 1.25rem;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.25rem;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.03);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .scope-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3rem;
        height: 3rem;
        border-radius: 12px;
        background: rgba(30, 58, 95, 0.08);
        color: var(--navy-900);
        font-size: 1.15rem;
        border: 1px solid rgba(30, 58, 95, 0.08);
    }

    .scope-card h3 {
        margin: 0;
        font-size: 1.08rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.5;
    }

    .scope-card p {
        margin: 0;
        color: var(--slate-600);
        font-size: .95rem;
        line-height: 1.7;
    }

    .visi-misi-wrap {
        background: #f8fafc;
        padding: 5rem 0;
        border-top: 0;
    }

    .visi-misi-card {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.5rem;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .visi-misi-header {
        display: flex;
        align-items: center;
        gap: .8rem;
        margin-bottom: 1rem;
    }

    .visi-misi-icon {
        width: 3rem;
        height: 3rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(30, 58, 95, 0.08);
        color: var(--navy-900);
        font-size: 1.2rem;
        border: 1px solid rgba(30, 58, 95, 0.08);
    }

    .visi-misi-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .visi-misi-card p,
    .visi-misi-card li {
        color: var(--slate-600);
        font-size: .96rem;
        line-height: 1.75;
    }

    .visi-misi-list {
        display: grid;
        gap: .9rem;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .visi-misi-list li {
        display: flex;
        gap: .8rem;
        align-items: flex-start;
    }

    .visi-misi-bullet {
        flex-shrink: 0;
        width: 1.9rem;
        height: 1.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(30, 58, 95, 0.08);
        color: var(--navy-900);
        font-weight: 700;
        font-size: .78rem;
    }

    .lp-performance-wrap {
        background: #fff;
        padding: 5rem 0;
    }

    .performance-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
        max-width: 80rem;
        margin: 0 auto;
    }

    .ba {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1.25rem;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .ba-photos {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0;
        background: #edf2f7;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    }

    .ba-photos figure {
        position: relative;
        overflow: hidden;
        background: #e5e7eb;
        min-height: 12rem;
    }

    .ba-photos img {
        width: 100%;
        height: 12rem;
        object-fit: cover;
        display: block;
    }

    .ba-cap {
        position: absolute;
        top: .75rem;
        left: .75rem;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 4.5rem;
        padding: .38rem .7rem;
        border-radius: 999px;
        background: rgba(30, 58, 95, 0.88);
        color: #fff;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .ba-cap-before { background: rgba(15, 23, 42, 0.72); }
    .ba-cap-after { background: rgba(30, 58, 95, 0.9); }

    .ba-body {
        padding: 1rem 1rem 1.1rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: .45rem;
    }

    .ba-body h3 {
        margin: 0;
        color: var(--navy-900);
        font-size: 1.02rem;
        font-weight: 800;
        line-height: 1.45;
    }

    .ba-body p {
        margin: 0;
        color: var(--slate-600);
        line-height: 1.6;
    }

    @media (max-width: 991.98px) {
        .procedure-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .scope-grid,
        .performance-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .procedure-grid,
        .scope-grid,
        .performance-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $featured = $completedReports->first();
    $others = $completedReports->skip(1);
    $fmt = fn ($n) => number_format($n, 0, ',', '.');

    $heroSlides = [
        [
            'image' => '/img/gmbar1.jpg',
            'alt' => 'Pemandangan desa Ghuangzou dan aktivitas warga',
        ],
        [
            'image' => '/img/gmbar2.jpg',
            'alt' => 'Lingkungan pedesaan yang asri dan tertata',
        ],
        [
            'image' => '/img/gmbar3.jpg',
            'alt' => 'Kehidupan masyarakat dan panorama alam desa',
        ],
    ];

    $visiMisi = [
        'visi' => 'Mewujudkan layanan pengaduan yang cepat, transparan, dan mudah diakses oleh semua warga.',
        'misi' => [
            'Menyediakan kanal pelaporan yang sederhana dan terbuka.',
            'Memastikan setiap laporan diteruskan ke petugas yang tepat.',
            'Menindaklanjuti laporan secara cepat dan terukur.',
            'Menyampaikan perkembangan penanganan secara transparan kepada pelapor.',
        ],
    ];
@endphp

<section class="lp-hero" id="top" aria-label="Hero SIGAP">
    <div class="lp-hero-slider" aria-live="polite">
        @foreach ($heroSlides as $index => $slide)
            <figure class="lp-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                <img src="{{ $slide['image'] }}" alt="{{ $slide['alt'] }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
            </figure>
        @endforeach

        <div class="lp-hero-overlay"></div>

        <div class="lp-hero-content">
            <div class="lp-hero-inner">
                <h1>Laporkan Keluhan Anda Melalui Platform SIGAP!</h1>
                <p class="lp-lead">Maju Bersama SIGAP, Wadah Keluhan Masyarakat Yang Efektif serta Inovatif</p>

                <div class="lp-hero-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Buka dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Buat laporan</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Masuk</a>
                    @endauth
                </div>

                @guest
                    <p class="lp-hero-note">Untuk membuat laporan, daftar dulu dengan nama, email, dan password.</p>
                @endguest
            </div>
        </div>

        <button class="lp-slider-btn prev" type="button" aria-label="Slide sebelumnya">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
        <button class="lp-slider-btn next" type="button" aria-label="Slide berikutnya">
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
</section>

<section id="cara-kerjanya" class="lp-section">
    <div class="container">
        <div class="section-intro text-center mx-auto">
            <div class="section-kicker">Alur Pengaduan</div>
            <h2>Prosedur Pelaporan</h2>
            <p class="lp-sub mx-auto">Dari laporan dikirim sampai masalah selesai, ada empat langkah yang jelas dan transparan.</p>
        </div>

        <ol class="procedure-grid list-unstyled mb-0">
            <li class="procedure-card">
                <div class="procedure-step">
                    <span class="procedure-step-badge">1</span>
                </div>
                <h3>Anda mengirim laporan</h3>
                <p>Pilih kategori, tulis masalahnya, lalu tambahkan foto dan alamat.</p>
            </li>
            <li class="procedure-card">
                <div class="procedure-step">
                    <span class="procedure-step-badge">2</span>
                </div>
                <h3>Admin memeriksa</h3>
                <p>Jika laporan ditolak, alasannya ditulis di halaman laporan Anda.</p>
            </li>
            <li class="procedure-card">
                <div class="procedure-step">
                    <span class="procedure-step-badge">3</span>
                </div>
                <h3>Petugas mengerjakan</h3>
                <p>Petugas sesuai kategori mengambil laporan, lalu mengunggah foto hasil pekerjaan.</p>
            </li>
            <li class="procedure-card">
                <div class="procedure-step">
                    <span class="procedure-step-badge">4</span>
                </div>
                <h3>Anda menilai hasilnya</h3>
                <p>Setelah selesai, beri nilai 1 sampai 5 dan tulis ulasan jika perlu.</p>
            </li>
        </ol>
    </div>
</section>

<section id="yang-bisa-dilaporkan" class="lp-scope-section lp-section">
    <div class="container">
        <div class="section-intro text-center mx-auto">
            <div class="section-kicker">Kategori Layanan</div>
            <h2>Ruang Lingkup</h2>
            <p class="lp-sub mx-auto">Pilih kategori yang paling sesuai agar laporan sampai ke petugas yang tepat.</p>
        </div>

        <div class="scope-grid">
            @foreach ($categories as $category)
                <div class="scope-card">
                    <div class="scope-icon">
                        @php
                            $iconMap = [
                                'Drainase & Saluran Air' => 'fa-solid fa-water',
                                'Infrastruktur & Jalan' => 'fa-solid fa-road',
                                'Kebersihan & Sampah' => 'fa-solid fa-trash',
                                'Penerangan Jalan' => 'fa-solid fa-lightbulb',
                                'Layanan Umum' => 'fa-solid fa-city',
                            ];
                            $icon = $iconMap[$category->name] ?? 'fa-solid fa-bolt';
                        @endphp
                        <i class="{{ $icon }}" aria-hidden="true"></i>
                    </div>
                    <h3>{{ $category->name }}</h3>
                    <p>{{ $category->description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="visi-misi" class="visi-misi-wrap">
    <div class="container">
        <div class="section-intro text-center mx-auto">
            <div class="section-kicker">Tentang Kami</div>
            <h2>Visi & Misi</h2>
            <p class="lp-sub mx-auto mb-0">Arah dan komitmen kami dalam melayani laporan Anda.</p>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="visi-misi-card h-100 p-4 p-lg-5">
                    <div class="visi-misi-header">
                        <span class="visi-misi-icon"><i class="fa-solid fa-bullseye" aria-hidden="true"></i></span>
                        <h3>VISI</h3>
                    </div>
                    <p class="mb-0">{{ $visiMisi['visi'] }}</p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="visi-misi-card h-100 p-4 p-lg-5">
                    <div class="visi-misi-header">
                        <span class="visi-misi-icon"><i class="fa-solid fa-check-double" aria-hidden="true"></i></span>
                        <h3>MISI</h3>
                    </div>
                    <ul class="visi-misi-list mb-0">
                        @foreach ($visiMisi['misi'] as $index => $item)
                            <li>
                                <span class="visi-misi-bullet">{{ $index + 1 }}</span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="hasil-pekerjaan" class="lp-performance-wrap">
    <div class="container">
        <div class="section-intro text-center mx-auto">
            <div class="section-kicker">Kinerja Terbaru</div>
            <h2>Capaian Kinerja</h2>
            <p class="lp-sub mx-auto">Foto sebelum dan sesudah dari laporan warga yang sudah dikerjakan petugas.</p>
        </div>

        @if ($stats['completed'] > 0)
            <div class="performance-grid">
                @foreach ($completedReports as $report)
                    <div>
                        @include('partials.before-after', ['report' => $report])
                    </div>
                @endforeach
            </div>
        @else
            <div class="lp-empty">
                <p class="fw-semibold mb-1">Belum ada laporan yang selesai.</p>
                <p class="text-muted mb-0">
                    Hasil pekerjaan petugas akan tampil di sini, lengkap dengan foto sebelum dan sesudah.
                </p>
            </div>
        @endif
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hero = document.querySelector('.lp-hero');
        const slides = Array.from(document.querySelectorAll('.lp-slide'));
        const dots = Array.from(document.querySelectorAll('.lp-dot'));
        const prevBtn = document.querySelector('.lp-slider-btn.prev');
        const nextBtn = document.querySelector('.lp-slider-btn.next');
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        let currentIndex = 0;
        let autoplayTimer = null;
        let touchStartX = 0;

        const setSlide = (index) => {
            currentIndex = (index + slides.length) % slides.length;

            slides.forEach((slide, idx) => {
                const active = idx === currentIndex;
                slide.classList.toggle('active', active);
                slide.setAttribute('aria-hidden', String(!active));
            });

            dots.forEach((dot, idx) => {
                const active = idx === currentIndex;
                dot.classList.toggle('active', active);
                dot.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        };

        const startAutoplay = () => {
            if (prefersReducedMotion || slides.length <= 1) return;
            stopAutoplay();
            autoplayTimer = window.setInterval(() => {
                setSlide(currentIndex + 1);
            }, 1000);
        };

        const stopAutoplay = () => {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        prevBtn?.addEventListener('click', () => {
            setSlide(currentIndex - 1);
            startAutoplay();
        });

        nextBtn?.addEventListener('click', () => {
            setSlide(currentIndex + 1);
            startAutoplay();
        });

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                setSlide(Number(dot.dataset.dot));
                startAutoplay();
            });
        });

        hero?.addEventListener('mouseenter', stopAutoplay);
        hero?.addEventListener('mouseleave', startAutoplay);
        hero?.addEventListener('focusin', stopAutoplay);
        hero?.addEventListener('focusout', startAutoplay);

        hero?.addEventListener('touchstart', (event) => {
            touchStartX = event.changedTouches[0].screenX;
            stopAutoplay();
        }, { passive: true });

        hero?.addEventListener('touchend', (event) => {
            const touchEndX = event.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;

            if (Math.abs(diff) > 40) {
                setSlide(diff < 0 ? currentIndex + 1 : currentIndex - 1);
            }

            startAutoplay();
        }, { passive: true });

        setSlide(0);
        startAutoplay();
    });
</script>
@endsection