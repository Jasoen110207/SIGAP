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
            --auth-bg: #f8fafc;
            --auth-surface: #ffffff;
            --auth-border: #e2e8f0;
            --auth-text: #0f172a;
            --auth-muted: #475569;
            --auth-primary: #1E3A5F;
        }

        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            background: var(--auth-bg);
            color: var(--auth-text);
            font-family: 'Plus Jakarta Sans', 'Public Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }

        .auth-screen {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #f8fafc;
            padding: 3rem 1rem;
        }

        .auth-shell {
            width: min(100%, 1100px);
        }

        .auth-card {
            background: var(--auth-surface);
            border: 1px solid var(--auth-border);
            border-radius: 1.5rem;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .auth-split-card {
            display: grid;
            grid-template-columns: 1fr;
            background: var(--auth-surface);
            border: 1px solid var(--auth-border);
            border-radius: 1.5rem;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .auth-split-card {
                grid-template-columns: 5fr 7fr;
            }
        }

        .auth-split-panel {
            min-height: 100%;
        }

        .auth-split-brand {
            background: linear-gradient(180deg, #0F294A 0%, #0b1d35 100%);
            color: #ffffff;
            padding: 2rem 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-brand-mark {
            display: inline-flex;
            width: 64px;
            height: 64px;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
        }

        .auth-brand-mark img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            display: block;
        }

        .auth-brand-title {
            margin-top: 1.25rem;
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.1;
        }

        .auth-brand-subtitle {
            margin-top: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .auth-brand-list {
            margin: 1.5rem 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 0.9rem;
            color: rgba(255,255,255,0.92);
            font-size: 0.92rem;
        }

        .auth-brand-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
        }

        .auth-brand-list li::before {
            content: "•";
            color: #fbbf24;
            font-size: 1.4rem;
            line-height: 1;
            margin-top: 0.05rem;
        }

        .auth-back-link {
            margin-top: 1.25rem;
            display: inline-block;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 0.92rem;
            transition: color 0.2s ease;
        }

        .auth-back-link:hover,
        .auth-back-link:focus-visible {
            color: #fbbf24;
            text-decoration: none;
        }

        .auth-split-form {
            background: #ffffff;
            padding: 2rem 1.75rem;
        }

        .auth-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.38rem 0.72rem;
            border-radius: 999px;
            background: #edf4ff;
            border: 1px solid rgba(30, 58, 95, 0.14);
            color: #0F294A;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .auth-role-title {
            margin-top: 1rem;
            margin-bottom: 0.4rem;
            font-size: clamp(1.8rem, 2vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -0.05em;
            color: #0f172a;
        }

        .auth-role-description {
            margin-bottom: 1.6rem;
            color: #475569;
            font-size: 0.96rem;
            line-height: 1.7;
        }

        .auth-form .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.55rem;
        }

        .auth-form .form-control,
        .auth-form .form-select {
            border-radius: 0.9rem;
            border: 1px solid #dfe6ee;
            padding: 0.8rem 0.9rem;
            background: #ffffff;
            color: #0f172a;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .auth-form .form-control:focus,
        .auth-form .form-select:focus {
            border-color: rgba(30, 58, 95, 0.55);
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 95, 0.10);
        }

        .auth-form .form-check {
            margin: 1rem 0 1.25rem;
        }

        .auth-form .form-check-label {
            color: #475569;
        }

        .auth-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            border: none;
            border-radius: 0.9rem;
            background: #0F294A;
            color: #ffffff;
            font-weight: 700;
            padding: 0.8rem 1rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .auth-btn-primary:hover,
        .auth-btn-primary:focus-visible {
            background: #1E3A5F;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .auth-form .small {
            color: #64748b;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.3rem 0 0.8rem;
            color: #94a3b8;
            font-size: 0.76rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: #e2e8f0;
        }

        .auth-form-links {
            margin-top: 0.5rem;
            font-size: 0.92rem;
            color: #475569;
        }

        .auth-form-links a {
            color: #0F294A;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-form-links a:hover,
        .auth-form-links a:focus-visible {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .auth-screen {
                padding: 2rem 0.75rem;
            }

            .auth-card .card-body {
                padding: 1.25rem;
            }
        }

        @media (max-width: 767.98px) {
            .auth-split-brand {
                padding: 1.5rem 1.25rem;
            }

            .auth-split-form {
                padding: 1.5rem 1.25rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <main class="auth-screen">
        <div class="auth-shell">
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
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            @yield('content')

            <div class="text-center">
                <a href="{{ url('/') }}" class="auth-back-link">← Kembali ke Halaman Utama</a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
