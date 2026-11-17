<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Petugas') - SIGAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --bg: #f3f6fb;
            --panel: #ffffff;
            --panel-soft: #f8fafc;
            --line: #e2e8f0;
            --ink: #1f2937;
            --muted: #64748b;
            --primary: #1f5fbf;
            --primary-soft: rgba(31, 95, 191, .1);
            --success: #198754;
            --warning: #f59e0b;
            --danger: #dc3545;
            --shadow: 0 12px 30px rgba(15, 23, 42, .08);
            --sidebar-bg: #0F294A;
            --sidebar-surface: rgba(255,255,255,0.06);
            --sidebar-border: rgba(255,255,255,0.10);
            --sidebar-text: #e2e8f0;
            --sidebar-text-soft: #94a3b8;
            --sidebar-text-strong: #ffffff;
            --sidebar-accent: #fbbf24;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Public Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }
        .admin-shell {
            min-height: 100vh;
            display: flex;
            background: var(--bg);
        }
        .admin-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            box-shadow: 0 1px 0 rgba(15, 23, 42, 0.02);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .brand {
            display: flex;
            align-items: center;
            padding: 1.1rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.10);
            background: rgba(15, 23, 42, 0.12);
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .brand-logo img {
            display: block;
            height: 40px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 6px 20px rgba(15, 23, 42, 0.22));
        }
        .brand-name {
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--ink);
            font-size: 1.5rem;
            line-height: 1.1;
        }
        .nav-group { padding: 1rem .75rem; }
        .nav-label {
            color: var(--sidebar-text-soft);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .35rem .75rem;
            margin-bottom: .5rem;
        }
        .admin-nav {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: .35rem;
            width: 100%;
        }
        .admin-nav li {
            width: 100%;
        }
        .admin-nav .nav-button {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
            border-radius: 12px;
            padding: .75rem .8rem;
            color: var(--sidebar-text);
            font-weight: 600;
            transition: .2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            overflow: hidden;
        }
        .admin-nav .nav-button:hover,
        .admin-nav .nav-button.active {
            background: rgba(255, 255, 255, 0.12);
            color: var(--sidebar-text-strong);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
        }
        .admin-nav .nav-button.active {
            background: rgba(255, 255, 255, 0.15);
            color: var(--sidebar-text-strong);
            font-weight: 700;
            border-left: 3px solid var(--sidebar-accent);
            padding-left: .7rem;
        }
        .admin-nav .nav-button .nav-label {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            min-width: 0;
            color: inherit;
            padding: 0;
            margin: 0;
            letter-spacing: 0;
            text-transform: none;
            font-size: inherit;
            font-weight: inherit;
        }
        .admin-nav .nav-button .nav-label i {
            width: 18px;
            text-align: center;
            flex-shrink: 0;
            color: inherit;
        }
        .admin-nav .nav-button .nav-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-nav .badge {
            min-width: 2.2rem;
            justify-content: center;
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
        }
        .admin-user {
            margin-top: auto;
            border-top: 1px solid var(--sidebar-border);
            padding: 1rem .85rem 1.2rem;
            background: rgba(255,255,255,0.02);
        }
        .admin-user-box {
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--sidebar-border);
            border-radius: 14px;
            padding: .75rem .8rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .admin-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,0.18), rgba(255,255,255,0.08));
            color: var(--sidebar-text-strong);
            display: grid;
            place-items: center;
            font-weight: 700;
        }
        .admin-content {
            flex: 1;
            min-width: 0;
            padding: 1.5rem 2rem 2rem;
        }
        .admin-topbar {
            background: rgba(255,255,255,0.7);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: .85rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(8px);
        }
        .topbar-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
        }
        .topbar-meta {
            color: var(--muted);
            font-size: .9rem;
        }
        .card-soft {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 18px;
            box-shadow: var(--shadow);
        }
        .stat-card {
            border: 0;
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.04);
        }
        .tab-panel {
            display: block;
        }
        .petugas-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            margin-bottom: 1rem;
        }
        .petugas-summary-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .4rem .65rem;
            border-radius: 999px;
            background: rgba(31,95,191,.08);
            color: var(--primary);
            font-weight: 700;
            font-size: .75rem;
        }
        .photo-cover {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .photo-compare {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .label-chip {
            position: absolute;
            top: .5rem;
            left: .5rem;
            font-size: .7rem;
            font-weight: 600;
        }
        @media (max-width: 991.98px) {
            .admin-shell { flex-direction: column; }
            .admin-sidebar {
                width: 100%;
                border-right: 0;
                border-bottom: 1px solid var(--sidebar-border);
            }
            .admin-content { padding: 1rem; }
            .admin-topbar { flex-direction: column; align-items: flex-start; gap: .5rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="brand">
                <a href="{{ route('dashboard') }}" class="brand-logo" aria-label="Dashboard SIGAP">
                    <img src="{{ asset('img/Gemini_Generated_Image_cmvdd5cmvdd5cmvd-removebg-preview.png') }}" alt="Logo SIGAP">
                </a>
            </div>

            <div class="nav-group">
                <div class="nav-label">Navigasi</div>
                <ul class="admin-nav">
                    <li>
                        <button type="button" class="nav-button active" data-tab="tugas-saya" aria-selected="true">
                            <span class="nav-label">
                                <i class="fa-solid fa-person-digging"></i>
                                <span class="nav-text">Tugas Saya</span>
                            </span>
                            <span class="badge text-bg-light text-dark rounded-pill">{{ $myReports->count() }}</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="nav-button" data-tab="antrean-kategori" aria-selected="false">
                            <span class="nav-label">
                                <i class="fa-solid fa-inbox"></i>
                                <span class="nav-text">Antrean Kategori</span>
                            </span>
                            <span class="badge bg-white/10 text-white rounded-pill">{{ $queueReports->count() }}</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="nav-button" data-tab="riwayat-selesai" aria-selected="false">
                            <span class="nav-label">
                                <i class="fa-solid fa-circle-check"></i>
                                <span class="nav-text">Riwayat Selesai</span>
                            </span>
                            <span class="badge bg-white/10 text-white rounded-pill">{{ $completedReports->total() }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="admin-user">
                <div class="admin-user-box">
                    <div class="admin-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold small text-truncate text-white">{{ auth()->user()->name ?? 'Petugas' }}</div>
                        <div class="text-white-50 small">Petugas Lapangan</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-3 d-grid">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="fa-solid fa-right-from-bracket me-1"></i>Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="admin-content">
            <div class="admin-topbar">
                <div>
                    <p class="topbar-title">@yield('title', 'Dashboard')</p>
                    <div class="topbar-meta">Sistem Informasi SIGAP</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-bg-primary-subtle text-primary">Petugas</span>
                </div>
            </div>

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

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
