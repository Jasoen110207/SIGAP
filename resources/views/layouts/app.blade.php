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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --ink: #1c2733;
            --muted: #5b6773;
            --line: #dfe3e8;
            --bg: #f6f7f8;
            --accent: #1f5fbf;
            --bs-primary: #1f5fbf;
            --bs-primary-rgb: 31, 95, 191;
            --bs-link-color: #1f5fbf;
            --bs-link-color-rgb: 31, 95, 191;
            --bs-link-hover-color: #184c99;
            --bs-link-hover-color-rgb: 24, 76, 153;
            --bs-body-color: #1c2733;
            --bs-body-font-family: 'Public Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }
        body { background-color: var(--bg); }
        .btn-primary {
            --bs-btn-bg: #1f5fbf; --bs-btn-border-color: #1f5fbf;
            --bs-btn-hover-bg: #184c99; --bs-btn-hover-border-color: #184c99;
            --bs-btn-active-bg: #143f80; --bs-btn-active-border-color: #143f80;
            --bs-btn-disabled-bg: #1f5fbf; --bs-btn-disabled-border-color: #1f5fbf;
        }
        .btn-outline-primary {
            --bs-btn-color: #1f5fbf; --bs-btn-border-color: #1f5fbf;
            --bs-btn-hover-bg: #1f5fbf; --bs-btn-hover-border-color: #1f5fbf;
            --bs-btn-active-bg: #184c99; --bs-btn-active-border-color: #184c99;
        }
        .site-nav .nav-link { color: var(--muted); }
        .site-nav .nav-link:hover,
        .site-nav .nav-link.active { color: var(--ink); }
        .site-nav .nav-link.active { font-weight: 600; }

        .stat-card { border: 0; box-shadow: 0 2px 10px rgba(0, 0, 0, .06); }
        .report-thumb { width: 56px; height: 56px; object-fit: cover; border-radius: .5rem; }
        .photo-cover { width: 100%; height: 180px; object-fit: cover; }
        .photo-compare { width: 100%; height: 150px; object-fit: cover; }
        .photo-modal { width: 100%; max-height: 320px; object-fit: cover; border-radius: .5rem; }
        .label-chip {
            position: absolute; top: .5rem; left: .5rem;
            font-size: .7rem; font-weight: 600;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom site-nav">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('landing') }}">SIGAP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}">Beranda</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @if (auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.petugas.index') ? 'active' : '' }}" href="{{ route('admin.petugas.index') }}">Kelola petugas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.audit-log.index') ? 'active' : '' }}" href="{{ route('admin.audit-log.index') }}">Log aktivitas</a>
                        </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-3">
                @auth
                    @php
                        $roleLabel = ['user' => 'Warga', 'admin' => 'Admin', 'petugas' => 'Petugas'][auth()->user()->role] ?? '';
                    @endphp
                    <li class="nav-item small text-muted">{{ auth()->user()->name }} ({{ $roleLabel }})</li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Keluar</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-primary" href="{{ route('register') }}">Daftar</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
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

<footer class="border-top bg-white">
    <div class="container py-4 small text-muted">
        SIGAP — Sistem Informasi &amp; Ganti Aksi Pelaporan Kelurahan
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
