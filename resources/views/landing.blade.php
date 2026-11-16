@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    :root {
        --hero-overlay: rgba(11, 27, 34, 0.42);
        --hero-shadow: rgba(7, 18, 24, 0.28);
    }

    .lp-hero {
        position: relative;
        width: 100vw;
        height: 100svh;
        min-height: 620px;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        overflow: hidden;
        background: #0e3b3d;
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
        background: linear-gradient(180deg, rgba(5, 20, 28, 0.42), rgba(4, 19, 25, 0.58)),
                    linear-gradient(90deg, rgba(7, 18, 24, 0.52), rgba(7, 18, 24, 0.18));
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
        background: #fff;
        border-color: #fff;
        color: var(--ink);
        box-shadow: 0 12px 24px rgba(255,255,255,.12);
    }

    .lp-hero .btn-primary:hover,
    .lp-hero .btn-primary:focus-visible {
        transform: translateY(-1px);
        background: #f2f6fb;
        border-color: #f2f6fb;
        color: var(--ink);
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
        flex: 1;
    }

    .ba-body > p:last-child,
    .ba-body > div:last-child {
        margin-top: auto;
        padding-top: 1rem;
    }

    .lp-empty { border: 1px dashed #c3cad2; border-radius: 6px; padding: 1.5rem; background: #fff; }
    .lp-caption { font-size: .9rem; color: var(--muted); margin: .6rem 0 0; }

    .visi-misi-wrap {
        background: #f6f7f9;
        border-top: 1px solid var(--line);
        padding: 3rem 0;
    }

    .visi-misi-card {
        background: #fff;
        border: 1px solid rgba(12, 25, 34, 0.08);
        border-radius: 18px;
        box-shadow: 0 8px 22px rgba(17, 24, 39, 0.04);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .visi-misi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(17, 24, 39, 0.08);
    }

    .visi-misi-header {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1rem;
    }

    .visi-misi-icon {
        width: 3rem;
        height: 3rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(31, 95, 191, 0.08);
        color: #0f766e;
        font-size: 1.2rem;
    }

    .visi-misi-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .visi-misi-card p,
    .visi-misi-card li {
        color: var(--muted);
        font-size: .96rem;
        line-height: 1.7;
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
        background: rgba(15, 118, 110, 0.08);
        color: #0f766e;
        font-weight: 700;
        font-size: .78rem;
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
            'image' => '/img/images.jpeg',
            'alt' => 'Pemandangan perkotaan dan lingkungan SIGAP',
        ],
        [
            'image' => '/img/images (1).jpeg',
            'alt' => 'Pemandangan alam dan sawah',
        ],
        [
            'image' => '/img/images (2).jpeg',
            'alt' => 'Pemandangan pedesaan dan alam sekitar',
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
    <h2>Prosedur Pelaporan</h2>
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

<section id="yang-bisa-dilaporkan" class="lp-section">
    <h2>Ruang Lingkup</h2>
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
    <section id="hasil-pekerjaan" class="lp-section">
        <h2>Capaian Kinerja</h2>
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
    <section id="hasil-pekerjaan" class="lp-section">
        <h2>Capaian Kinerja</h2>
        <p class="lp-sub">Foto sebelum dan sesudah dari laporan warga yang sudah dikerjakan petugas</p>
        <div class="lp-empty">
            <p class="fw-semibold mb-1">Belum ada laporan yang selesai.</p>
            <p class="text-muted mb-0">
                Hasil pekerjaan petugas akan tampil di sini, lengkap dengan foto sebelum dan sesudah.
            </p>
        </div>
    </section>
@endif

<section id="visi-misi" class="visi-misi-wrap">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="mb-1">Tentang Kami</h2>
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
            }, 5500);
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