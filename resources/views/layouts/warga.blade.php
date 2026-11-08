<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Warga') - SIGAP</title>
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
            background: var(--panel);
            border-right: 1px solid var(--line);
            box-shadow: 0 1px 0 rgba(15, 23, 42, 0.02);
            display: flex;
            flex-direction: column;
        }
        .brand {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid var(--line);
        }
        .brand-name {
            font-weight: 800;
            letter-spacing: -.03em;
            color: var(--ink);
            font-size: 1.5rem;
            line-height: 1.1;
        }
        .nav-group { padding: 1rem .85rem; }
        .nav-label {
            color: var(--muted);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .35rem .8rem;
            margin-bottom: .5rem;
        }
        .admin-nav {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: .3rem;
        }
        .admin-nav a {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem .8rem;
            border-radius: 12px;
            color: var(--ink);
            text-decoration: none;
            font-weight: 600;
            transition: .2s ease;
        }
        .admin-nav a:hover,
        .admin-nav a.active {
            background: var(--primary-soft);
            color: var(--primary);
        }
        .admin-user {
            margin-top: auto;
            border-top: 1px solid var(--line);
            padding: 1rem .85rem 1.2rem;
        }
        .admin-user-box {
            background: var(--panel-soft);
            border: 1px solid var(--line);
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
            background: linear-gradient(135deg, #dfeafc, #eff5ff);
            color: var(--primary);
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
        .report-thumb {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
        }
        .photo-modal {
            width: 100%;
            max-height: 260px;
            object-fit: cover;
            border-radius: 8px;
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
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="brand">
                <div class="brand-name">SIGAP</div>
            </div>

            <div class="nav-group">
                <div class="nav-label">Navigasi</div>
                <ul class="admin-nav">
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('warga.profile') }}" class="{{ request()->routeIs('warga.profile') ? 'active' : '' }}">
                            <span>Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="admin-user">
                <div class="admin-user-box">
                    <div class="admin-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'W', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold small text-truncate">{{ auth()->user()->name ?? 'Warga' }}</div>
                        <div class="text-muted small">Warga</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-3 d-grid">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
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
                    <span class="badge text-bg-primary-subtle text-primary">Warga</span>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
