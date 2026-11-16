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
            --nav-bg: #0e3b3d;
            --nav-bg-soft: #123f41;
            --nav-accent: #e7f4f4;
            --nav-accent-strong: #ffffff;
            --nav-muted: rgba(255,255,255,.75);
            --bs-primary: #1f5fbf;
            --bs-primary-rgb: 31, 95, 191;
            --bs-link-color: #1f5fbf;
            --bs-link-color-rgb: 31, 95, 191;
            --bs-link-hover-color: #184c99;
            --bs-link-hover-color-rgb: 24, 76, 153;
            --bs-body-color: #1c2733;
            --bs-body-font-family: 'Public Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body { background-color: var(--bg); }

        section[id], div[id] {
            scroll-margin-top: 88px;
        }

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

        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            background: rgba(14, 59, 61, 0.08);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: background-color .3s ease, border-color .3s ease, box-shadow .3s ease;
        }

        .site-header.navbar-solid {
            background: rgba(14, 59, 61, 0.96);
            box-shadow: 0 12px 30px rgba(14, 59, 61, 0.18);
            border-bottom-color: rgba(255,255,255,0.08);
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
            filter: drop-shadow(0 4px 16px rgba(0,0,0,.18));
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
            color: rgba(255,255,255,.82);
            font-weight: 600;
            font-size: .92rem;
            padding: .85rem 1rem;
            transition: color .2s ease;
            text-shadow: 0 1px 2px rgba(0,0,0,.2);
        }

        .site-header .nav-link:hover,
        .site-header .nav-link:focus-visible,
        .site-header .nav-link.active {
            color: #fff;
            text-decoration: none;
            outline: none;
        }

        .site-header .nav-link::after {
            content: "";
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: .4rem;
            height: 2px;
            background: #fff;
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
            background: linear-gradient(135deg, #1f5fbf 0%, #2d9cdb 35%, #1d4ed8 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 8px 20px rgba(31, 95, 191, 0.18);
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
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg site-header" aria-label="Navigasi utama">
    <div class="container d-flex align-items-center justify-content-between w-100 px-6 px-md-12">
        <a class="brand-identity" href="#top" aria-label="Kembali ke bagian atas halaman">
            <img src="{{ asset('img/Gemini_Generated_Image_cmvdd5cmvdd5cmvd-removebg-preview.png') }}" alt="Logo SIGAP" class="brand-logo-img h-8 md:h-10" loading="eager">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="mainNav">
            <ul class="navbar-nav gap-lg-2 ms-auto mb-0">
                <li class="nav-item">
                    <a class="nav-link" href="#cara-kerjanya" data-scroll-link aria-current="false">Prosedur Pelaporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#yang-bisa-dilaporkan" data-scroll-link aria-current="false">Ruang Lingkup</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#hasil-pekerjaan" data-scroll-link aria-current="false">Capaian Kinerja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#visi-misi" data-scroll-link aria-current="false">Tentang Kami</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4" id="top">
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

<footer style="background-color: #163A38; color: #ffffff;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 48px 24px;">
        <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 32px; align-items: start;">
            <div style="display: flex; align-items: flex-start; gap: 16px;">
                <img src="{{ asset('img/Gemini_Generated_Image_cmvdd5cmvdd5cmvd-removebg-preview.png') }}" alt="Logo SIGAP" style="height: 55px; width: auto; object-fit: contain; flex-shrink: 0;">
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #ffffff; line-height: 1.4;">Platform Layanan SIGAP</h3>
                    <p style="margin: 10px 0 0; font-size: 0.9rem; line-height: 1.7; color: rgba(255,255,255,0.8);">Pemerintah Desa [Nama Desa]</p>
                    <p style="margin: 10px 0 0; font-size: 0.9rem; line-height: 1.7; color: rgba(255,255,255,0.8);">[Alamat Kantor Desa, Kecamatan, Kabupaten, Provinsi, Kode Pos]</p>
                    <p style="margin: 14px 0 0; font-size: 0.9rem; font-weight: 600; color: #ffffff;">
                        Kode Wilayah: <span style="font-weight: 400; color: rgba(255,255,255,0.8);">[Kode Wilayah Desa]</span>
                    </p>
                </div>
            </div>

            <div>
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #ffffff;">Hubungi Kami</h4>
                <ul style="list-style: none; padding: 0; margin: 16px 0 0; display: grid; gap: 12px; color: rgba(255,255,255,0.8); font-size: 0.9rem;">
                    <li style="display: flex; align-items: flex-start; gap: 10px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px; flex-shrink: 0; color: #ffffff;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3.5 5.5a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-9a2 2 0 0 1-2-2v-9Z"/>
                            <path d="m3.75 6.25 6.25 5 6.25-5"/>
                        </svg>
                        <a href="tel:+6281234567890" style="color: rgba(255,255,255,0.85); text-decoration: none;">+62 812-3456-7890</a>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 10px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px; flex-shrink: 0; color: #ffffff;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3.5 5.5a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-9a2 2 0 0 1-2-2v-9Z"/>
                            <path d="M3.75 6.5 10 10.75l6.25-4.25"/>
                        </svg>
                        <a href="mailto:info@sigap.id" style="color: rgba(255,255,255,0.85); text-decoration: none; word-break: break-all;">info@sigap.id</a>
                    </li>
                    <li style="display: flex; align-items: flex-start; gap: 10px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px; flex-shrink: 0; color: #ffffff;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M10 17.5c4.2 0 7.5-3.3 7.5-7.5S14.2 2.5 10 2.5 2.5 5.8 2.5 10s3.3 7.5 7.5 7.5Z"/>
                            <path d="M6.5 8.5C6.5 7 7.7 5.8 9.2 5.8h1.6c1.5 0 2.7 1.2 2.7 2.7v2.8c0 1.6-1.2 2.8-2.7 2.8h-1.6c-1.5 0-2.7-1.2-2.7-2.8V8.5Z"/>
                        </svg>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" style="color: rgba(255,255,255,0.85); text-decoration: none;">@sigapdesa</a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #ffffff;">Nomor Telepon Penting</h4>
                <ul style="list-style: none; padding: 0; margin: 16px 0 0; display: grid; gap: 10px; font-size: 0.9rem;">
                    <li><a href="tel:+6281234567890" style="color: rgba(255,255,255,0.8); text-decoration: underline; text-underline-offset: 4px;">Kontak Pelayanan Desa</a></li>
                    <li><a href="tel:+6281234567891" style="color: rgba(255,255,255,0.8); text-decoration: underline; text-underline-offset: 4px;">Babinsa / Bhabinkamtibmas</a></li>
                    <li><a href="tel:+6281234567892" style="color: rgba(255,255,255,0.8); text-decoration: underline; text-underline-offset: 4px;">Puskesmas</a></li>
                </ul>
            </div>

            <div>
                <h4 style="margin: 0; font-size: 1rem; font-weight: 700; color: #ffffff;">Jelajahi</h4>
                <ul style="list-style: none; padding: 0; margin: 16px 0 0; display: grid; gap: 10px; font-size: 0.9rem;">
                    <li><a href="https://kemendesa.go.id" target="_blank" rel="noopener noreferrer" style="color: rgba(255,255,255,0.8); text-decoration: underline; text-underline-offset: 4px;">Website Kemendesa</a></li>
                    <li><a href="https://kemendagri.go.id" target="_blank" rel="noopener noreferrer" style="color: rgba(255,255,255,0.8); text-decoration: underline; text-underline-offset: 4px;">Website Kemendagri</a></li>
                    <li><a href="https://www.kabupaten-lainnya.go.id" target="_blank" rel="noopener noreferrer" style="color: rgba(255,255,255,0.8); text-decoration: underline; text-underline-offset: 4px;">Website Pemerintah Kabupaten</a></li>
                    <li><a href="https://cekbansos.kemensos.go.id" target="_blank" rel="noopener noreferrer" style="color: rgba(255,255,255,0.8); text-decoration: underline; text-underline-offset: 4px;">Cek Bansos Kemensos</a></li>
                </ul>
            </div>
        </div>

        <div style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 20px; text-align: center; font-size: 0.85rem; color: rgba(255,255,255,0.9);">
            © 2026 Platform SIGAP — Sistem Informasi Pengaduan Masyarakat
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            footer > div > div {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
@stack('scripts')
</body>
</html>
