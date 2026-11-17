<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIGAP') — Sistem Informasi & Ganti Aksi Pelaporan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --ink: #1c2733;
            --muted: #5b6773;
            --line: #dfe3e8;
            --bg: #f6f7f8;
            --navy-900: #1E3A5F;
            --navy-800: #162C49;
            --navy-50: #edf4ff;
            --slate-700: #475569;
            --slate-300: #cbd5e1;
            --slate-900: #0f172a;
            --amber-500: #f59e0b;
            --nav-bg: #0e3b3d;
            --nav-bg-soft: #123f41;
            --nav-accent: #e7f4f4;
            --nav-accent-strong: #ffffff;
            --nav-muted: rgba(255,255,255,.75);
            --bs-primary: #1E3A5F;
            --bs-primary-rgb: 30, 58, 95;
            --bs-link-color: #1E3A5F;
            --bs-link-color-rgb: 30, 58, 95;
            --bs-link-hover-color: #162C49;
            --bs-link-hover-color-rgb: 22, 44, 73;
            --bs-body-color: #1c2733;
            --bs-body-font-family: 'Plus Jakarta Sans', 'Public Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --bg-soft: #f8fafc;
            --surface: #ffffff;
            --surface-alt: #f8fafc;
            --text-strong: #0f172a;
            --text-muted: #475569;
            --emerald-700: #16332e;
            --line-soft: rgba(15, 23, 42, 0.08);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg);
            font-family: 'Plus Jakarta Sans', 'Public Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }

        section[id], div[id] {
            scroll-margin-top: 88px;
        }

        .btn-primary {
            --bs-btn-bg: #1E3A5F; --bs-btn-border-color: #1E3A5F;
            --bs-btn-hover-bg: #162C49; --bs-btn-hover-border-color: #162C49;
            --bs-btn-active-bg: #112744; --bs-btn-active-border-color: #112744;
            --bs-btn-disabled-bg: #1E3A5F; --bs-btn-disabled-border-color: #1E3A5F;
        }
        .btn-outline-primary {
            --bs-btn-color: #1E3A5F; --bs-btn-border-color: #1E3A5F;
            --bs-btn-hover-bg: #1E3A5F; --bs-btn-hover-border-color: #1E3A5F;
            --bs-btn-active-bg: #162C49; --bs-btn-active-border-color: #162C49;
        }

        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            background: linear-gradient(180deg, #0F294A 0%, #102d4d 100%);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.10);
            transition: background-color .3s ease, border-color .3s ease, box-shadow .3s ease;
        }

        .site-header.navbar-solid {
            background: linear-gradient(180deg, #0F294A 0%, #0d2341 100%);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
            border-bottom-color: rgba(255,255,255,0.12);
        }

        .site-header .navbar {
            min-height: 72px;
        }

        .site-header .container,
        .site-header .container-fluid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: none;
            gap: 1rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .site-header .navbar-nav {
            align-items: center;
            margin-left: auto;
            margin-right: 0;
        }

        .site-header .navbar-collapse {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: auto;
            flex-grow: 1;
        }

        .brand-identity {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            padding: .25rem 0;
        }

        .brand-logo-img {
            display: block;
            height: 42px;
            width: auto;
            max-width: 240px;
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(0,0,0,.08));
        }

        @media (max-width: 991.98px) {
            .site-header .container,
            .site-header .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .brand-logo-img {
                height: 34px;
                max-width: 180px;
            }
        }

        .site-header .nav-link {
            position: relative;
            color: #cbd5e1;
            font-weight: 600;
            font-size: .92rem;
            padding: .85rem 1rem;
            transition: color .2s ease;
        }

        .site-header .nav-link:hover,
        .site-header .nav-link:focus-visible,
        .site-header .nav-link.active {
            color: #ffffff;
            text-decoration: none;
            outline: none;
            font-weight: 700;
        }

        .site-header .nav-link::after {
            content: "";
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: .4rem;
            height: 2px;
            background: #fbbf24;
            border-radius: 999px;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform .2s ease;
        }

        .site-header .nav-link:hover::after,
        .site-header .nav-link:focus-visible::after,
        .site-header .nav-link.active::after {
            transform: scaleX(1);
        }

        @media (max-width: 991.98px) {
            .brand-name {
                font-size: 1.1rem;
            }

            .brand-sub {
                display: none;
            }

            .site-header .navbar-collapse {
                padding-top: .75rem;
                display: block;
                margin-left: 0;
                width: 100%;
                flex-grow: 0;
            }

            .site-header .navbar-nav {
                align-items: flex-start;
                width: 100%;
                margin: 0;
            }

            .site-header .nav-link {
                padding-left: .25rem;
                padding-right: .25rem;
            }

            .site-header .nav-link::after {
                left: 0;
                right: 0;
            }
        }

        .app-auth-header {
            position: sticky;
            top: 0;
            z-index: 1040;
            background: linear-gradient(180deg, #0f294a 0%, #102847 100%);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14);
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }

        .app-auth-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 76px;
            padding-top: .6rem;
            padding-bottom: .6rem;
        }

        .app-auth-brand {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .app-auth-brand img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            border-radius: 10px;
            background: rgba(255,255,255,0.08);
            padding: .3rem;
        }

        .app-auth-brand span {
            font-size: .9rem;
            line-height: 1.1;
        }

        .app-auth-summary {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #e2e8f0;
            margin-left: auto;
            margin-right: auto;
        }

        .app-auth-summary small {
            display: block;
            color: rgba(255,255,255,0.72);
            letter-spacing: .05em;
            text-transform: uppercase;
            font-size: .68rem;
            font-weight: 700;
        }

        .app-auth-summary strong {
            display: block;
            color: #ffffff;
            font-size: 1rem;
            line-height: 1.2;
        }

        .app-auth-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(251, 191, 36, 0.16);
            color: #fce7a5;
            border: 1px solid rgba(251, 191, 36, 0.32);
            border-radius: 999px;
            padding: .35rem .7rem;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .app-auth-logout {
            margin-left: auto;
        }

        .app-auth-logout .btn {
            border-radius: 999px;
            padding: .6rem 1rem;
            font-weight: 600;
        }

        .app-auth-main {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        @media (max-width: 767.98px) {
            .app-auth-header-inner {
                flex-wrap: wrap;
                justify-content: center;
                padding-top: .75rem;
                padding-bottom: .75rem;
            }

            .app-auth-summary {
                width: 100%;
                justify-content: center;
                text-align: center;
                margin: 0;
            }

            .app-auth-logout {
                margin-left: 0;
            }
        }

        .site-nav .nav-link { color: var(--muted); }
        .site-nav .nav-link:hover,
        .site-nav .nav-link.active { color: var(--ink); }
        .site-nav .nav-link.active { font-weight: 600; }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            margin-right: .5rem;
            padding: .4rem .8rem;
            border-radius: 999px;
            text-decoration: none;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .brand-logo:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }
        .brand-word {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            line-height: 1;
            background: linear-gradient(135deg, #1E3A5F 0%, #2d9cdb 35%, #1d4ed8 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 8px 20px rgba(30, 58, 95, 0.18);
        }

        .stat-card { border: 0; box-shadow: 0 2px 10px rgba(0, 0, 0, .06); }
        .report-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: .5rem; }
        .photo-cover { width: 100%; height: 180px; object-fit: cover; }
        .photo-compare { width: 100%; height: 150px; object-fit: cover; }
        .photo-modal { width: 100%; max-height: 320px; object-fit: cover; border-radius: .5rem; }
        .label-chip {
            position: absolute; top: .5rem; left: .5rem;
            font-size: .7rem; font-weight: 600;
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        footer,
        footer p,
        footer span,
        footer li,
        footer a,
        footer svg {
            color: rgb(226 232 240) !important;
        }

        footer h3,
        footer h4,
        footer .text-white,
        footer .text-white-50 {
            color: #ffffff !important;
        }

        .footer-link {
            text-decoration: none;
            transition: color 0.2s ease, opacity 0.2s ease, transform 0.2s ease;
            color: rgb(226 232 240) !important;
        }

        .footer-link:hover,
        .footer-link:focus-visible,
        .footer-link:hover span,
        .footer-link:focus-visible span,
        .footer-link:hover svg,
        .footer-link:focus-visible svg {
            color: #fbbf24 !important;
            text-decoration: none;
            opacity: 1;
            transform: translateX(2px);
        }

        footer svg {
            stroke: currentColor;
        }

        @media (max-width: 991.98px) {
            .footer-link span,
            .footer-link {
                word-break: break-word;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $isPublicLayout = request()->routeIs([
        'landing',
        'login',
        'login.attempt',
        'register',
        'register.store',
        'password.request',
        'password.email',
        'password.reset',
        'password.update',
        'petugas.login',
        'petugas.login.attempt',
        'admin.login',
        'admin.login.attempt',
    ]);
    $showDashboardHeader = request()->routeIs('dashboard') && auth()->check();
@endphp

@if ($showDashboardHeader)
<header class="app-auth-header">
    <div class="container app-auth-header-inner">
        <a class="app-auth-brand" href="{{ route('dashboard') }}" aria-label="Dashboard SIGAP">
            <img src="{{ asset('img/Gemini_Generated_Image_cmvdd5cmvdd5cmvd-removebg-preview.png') }}" alt="Logo SIGAP">
            <span>SIGAP</span>
        </a>

        <div class="app-auth-summary">
            <span class="app-auth-badge">{{ auth()->user()->role ?? 'Petugas' }}</span>
            <div>
                <small>Petugas</small>
                <strong>{{ auth()->user()->name ?? 'Petugas' }}</strong>
            </div>
        </div>

        <div class="app-auth-logout">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-light btn-sm fw-semibold">
                    <i class="fa-solid fa-right-from-bracket me-1"></i>Logout
                </button>
            </form>
        </div>
    </div>
</header>
@endif

@if ($isPublicLayout)
<nav class="navbar navbar-expand-lg site-header bg-[#0F294A] border-b border-white/10 sticky top-0 z-50" aria-label="Navigasi utama">
    <div class="container d-flex align-items-center justify-content-between w-100 px-6 px-md-12">
        <a class="brand-identity" href="#top" aria-label="Kembali ke bagian atas halaman">
            <img src="{{ asset('img/Gemini_Generated_Image_cmvdd5cmvdd5cmvd-removebg-preview.png') }}" alt="Logo SIGAP" class="brand-logo-img h-8 md:h-10" loading="eager">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-icon" style="filter: brightness(10);"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="mainNav">
            <ul class="navbar-nav gap-lg-2 ms-auto mb-0">
                <li class="nav-item">
                    <a class="nav-link text-slate-200 hover:text-white" href="#cara-kerjanya" data-scroll-link aria-current="false">Prosedur Pelaporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-slate-200 hover:text-white" href="#yang-bisa-dilaporkan" data-scroll-link aria-current="false">Ruang Lingkup</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-slate-200 hover:text-white" href="#visi-misi" data-scroll-link aria-current="false">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-slate-200 hover:text-white" href="#hasil-pekerjaan" data-scroll-link aria-current="false">Capaian Kinerja</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
@endif

<main class="{{ $showDashboardHeader ? 'container app-auth-main py-4' : 'container py-4' }}" id="top">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @yield('content')
</main>

@if ($isPublicLayout)
<footer class="bg-slate-900 text-slate-200 border-t border-slate-800" style="background: linear-gradient(180deg, #0F294A 0%, #0b1d35 100%);">
    <div class="container py-5 py-md-6" style="max-width: 1200px;">
        <div class="row g-4 g-lg-5 align-items-start">
            <div class="col-12 col-lg-4">
                <div class="d-flex align-items-start gap-3 gap-lg-4">
                    <img src="{{ asset('img/Gemini_Generated_Image_cmvdd5cmvdd5cmvd-removebg-preview.png') }}" alt="Logo SIGAP" class="img-fluid" style="height: 58px; width: auto; object-fit: contain; flex-shrink: 0;">
                    <div>
                        <h3 class="mb-2 text-white fw-bold" style="font-size: 1.05rem; line-height: 1.4;">Platform Layanan SIGAP</h3>
                        <p class="mb-2 !text-slate-200 text-slate-200" style="font-size: 0.9rem; line-height: 1.7; margin: 0;">Pemerintah Desa Ghuangzou</p>
                        <p class="mb-2 !text-slate-200 text-slate-200" style="font-size: 0.9rem; line-height: 1.7; margin: 0;">Chernobyl, Ghuangzou, Taipei, China</p>
                        <p class="mb-0 !text-slate-200 text-slate-200 fw-semibold" style="font-size: 0.9rem; line-height: 1.7;">
                            Kode Wilayah: <span class="!text-slate-200 text-slate-200 fw-normal">911</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-2">
                <h4 class="text-white fw-bold mb-3 mb-lg-4" style="font-size: 1rem;">Hubungi Kami</h4>
                <ul class="list-unstyled d-grid gap-3 mb-0" style="font-size: 0.9rem;">
                    <li>
                        <a href="tel:+9188777829892" class="footer-link d-inline-flex align-items-start gap-2 !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">
                            <svg class="flex-shrink-0 !text-slate-200 text-slate-200" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" style="margin-top: 2px;">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.08 4.18 2 2 0 0 1 5.06 2h3a2 2 0 0 1 2 1.72l.43 2.08a2 2 0 0 1-.55 1.92L8.8 9.16a16 16 0 0 0 6.05 6.05l1.44-1.14a2 2 0 0 1 1.92-.55l2.08.43A2 2 0 0 1 22 16.92Z"/>
                            </svg>
                            <span class="!text-slate-200 text-slate-200">+91 88777829892</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:info@ghuangzoumerdeka.org" class="footer-link d-inline-flex align-items-start gap-2 !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">
                            <svg class="flex-shrink-0 !text-slate-200 text-slate-200" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" style="margin-top: 2px;">
                                <path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11A2.5 2.5 0 0 1 17.5 20h-11A2.5 2.5 0 0 1 4 17.5v-11Z"/>
                                <path d="m4.75 7 7.25 5.5 7.25-5.5"/>
                            </svg>
                            <span class="!text-slate-200 text-slate-200">@ghuangmerdeka.org</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com/ghuangzoumerdeka" target="_blank" rel="noopener noreferrer" class="footer-link d-inline-flex align-items-start gap-2 !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">
                            <svg class="flex-shrink-0 !text-slate-200 text-slate-200" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" style="margin-top: 2px;">
                                <rect x="3.5" y="3.5" width="17" height="17" rx="4"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                            </svg>
                            <span class="!text-slate-200 text-slate-200">@GhuangzouMerdeka</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h4 class="text-white fw-bold mb-3 mb-lg-4" style="font-size: 1rem;">Layanan Penting</h4>
                <ul class="list-unstyled d-grid gap-2 mb-0" style="font-size: 0.9rem;">
                    <li><a href="tel:+9188777829892" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Kontak Pelayanan Desa</a></li>
                    <li><a href="https://ghuangzoumerdeka.org" target="_blank" rel="noopener noreferrer" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Website Resmi Desa</a></li>
                    <li><a href="https://instagram.com/ghuangzoumerdeka" target="_blank" rel="noopener noreferrer" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Instagram Desa</a></li>
                    <li><a href="#" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Pusat Informasi Desa</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h4 class="text-white fw-bold mb-3 mb-lg-4" style="font-size: 1rem;">Tautan Terkait</h4>
                <ul class="list-unstyled d-grid gap-2 mb-0" style="font-size: 0.9rem;">
                    <li><a href="https://ghuangzou.org" target="_blank" rel="noopener noreferrer" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Website Resmi</a></li>
                    <li><a href="https://instagram.com/ghuangzoumerdeka" target="_blank" rel="noopener noreferrer" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Instagram @GhuangzouMerdeka</a></li>
                    <li><a href="#" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Layanan Publik Desa</a></li>
                    <li><a href="#" class="footer-link !text-slate-200 text-slate-200 hover:!text-amber-400 hover:text-amber-400">Informasi Pemerintahan</a></li>
                </ul>
            </div>
        </div>

        <div class="border-top border-slate-800 mt-4 pt-4 text-center small !text-slate-200 text-slate-200" style="letter-spacing: 0.01em;">
            © 2026 Platform SIGAP — Sistem Informasi Pengaduan Masyarakat
        </div>
    </div>

    <style>
        .footer-link {
            text-decoration: none;
            transition: color 0.2s ease, opacity 0.2s ease, transform 0.2s ease;
        }

        .footer-link:hover,
        .footer-link:focus-visible {
            color: #fbbf24 !important;
            text-decoration: none;
            opacity: 1;
            transform: translateX(2px);
        }

        @media (max-width: 991.98px) {
            .footer-link span,
            .footer-link {
                word-break: break-word;
            }
        }
    </style>
</footer>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@if ($isPublicLayout)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.querySelector('.site-header');
        const links = Array.from(document.querySelectorAll('[data-scroll-link]'));
        const sections = links
            .map((link) => document.querySelector(link.getAttribute('href')))
            .filter(Boolean);

        const setActiveLink = (activeLink) => {
            links.forEach((link) => {
                const isActive = link === activeLink;
                link.classList.toggle('active', isActive);
                link.setAttribute('aria-current', isActive ? 'page' : 'false');
            });
        };

        const applyHeaderState = () => {
            const shouldSolid = window.scrollY > 40;
            header.classList.toggle('navbar-solid', shouldSolid);
        };

        applyHeaderState();
        window.addEventListener('scroll', applyHeaderState, { passive: true });

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        links.forEach((link) => {
            link.addEventListener('click', function (event) {
                const target = document.querySelector(this.getAttribute('href'));
                if (!target) return;

                event.preventDefault();
                const behavior = prefersReducedMotion ? 'auto' : 'smooth';
                target.scrollIntoView({ behavior, block: 'start' });
                setActiveLink(this);

                const navCollapse = document.getElementById('mainNav');
                if (navCollapse && navCollapse.classList.contains('show')) {
                    const collapse = bootstrap.Collapse.getOrCreateInstance(navCollapse);
                    collapse.hide();
                }
            });
        });

        if (sections.length) {
            const observer = new IntersectionObserver(
                (entries) => {
                    const visible = entries
                        .filter((entry) => entry.isIntersecting)
                        .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

                    if (!visible) return;

                    const activeId = `#${visible.target.id}`;
                    const activeLink = links.find((link) => link.getAttribute('href') === activeId);
                    if (activeLink) setActiveLink(activeLink);
                },
                {
                    threshold: [0.2, 0.45, 0.7],
                    rootMargin: '-12% 0px -30% 0px'
                }
            );

            sections.forEach((section) => observer.observe(section));
        }
    });
</script>
@endif
@stack('scripts')
</body>
</html>
