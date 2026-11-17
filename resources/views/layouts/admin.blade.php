<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SIGAP</title>
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
        .btn-primary {
            --bs-btn-bg: #0F294A;
            --bs-btn-border-color: #0F294A;
            --bs-btn-hover-bg: #1E3A5F;
            --bs-btn-hover-border-color: #1E3A5F;
            --bs-btn-active-bg: #112744;
            --bs-btn-active-border-color: #112744;
            --bs-btn-disabled-bg: #0F294A;
            --bs-btn-disabled-border-color: #0F294A;
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
            border-bottom: 1px solid var(--sidebar-border);
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
            padding: 0 .75rem;
            display: grid;
            gap: .35rem;
            width: 100%;
            overflow: hidden;
        }
        .admin-nav li {
            width: 100%;
        }
        .admin-nav a {
            display: flex;
            align-items: center;
            gap: .75rem;
            width: 100%;
            padding: .75rem .8rem;
            border-radius: 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 600;
            transition: .2s ease;
            overflow: hidden;
        }
        .admin-nav a:hover,
        .admin-nav a.active {
            background: rgba(255, 255, 255, 0.12);
            color: var(--sidebar-text-strong);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
        }
        .admin-nav a.active {
            background: rgba(255, 255, 255, 0.15);
            color: var(--sidebar-text-strong);
            font-weight: 700;
            border-left: 3px solid var(--sidebar-accent);
            padding-left: .7rem;
        }
        .admin-nav a .nav-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            color: var(--sidebar-text-strong);
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
        .verification-table {
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 0;
        }
        .verification-table th,
        .verification-table td {
            vertical-align: top;
            white-space: normal;
        }
        .report-photo-cell {
            width: 110px;
        }
        .report-thumb-link {
            display: inline-flex;
            width: 84px;
            height: 84px;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            background: var(--panel-soft);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
        }
        .report-thumb {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .report-summary {
            display: grid;
            gap: .35rem;
        }
        .report-title {
            line-height: 1.4;
            word-break: break-word;
        }
        .report-desc {
            margin: 0;
            color: var(--muted);
            font-size: .875rem;
            line-height: 1.5;
        }
        @media (max-width: 991.98px) {
            .admin-shell { flex-direction: column; }
            .admin-sidebar {
                width: 100%;
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }
            .admin-content { padding: 1rem; }
            .admin-topbar { flex-direction: column; align-items: flex-start; gap: .5rem; }
        }
        @media (max-width: 575.98px) {
            .report-photo-cell {
                width: 82px;
            }
            .report-thumb-link {
                width: 72px;
                height: 72px;
            }
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
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.petugas.index') }}" class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
                            <span class="nav-text">Kelola Petugas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.audit-log.index') }}" class="{{ request()->routeIs('admin.audit-log.index') ? 'active' : '' }}">
                            <span class="nav-text">Log Aktivitas</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="admin-user">
                <div class="admin-user-box">
                    <div class="admin-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold small text-truncate text-white">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="text-white-50 small">Admin Kelurahan</div>
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
                    <span class="badge text-bg-primary-subtle text-primary">Admin</span>
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
