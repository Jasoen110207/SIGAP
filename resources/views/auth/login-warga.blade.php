@extends('layouts.auth')

@section('title', 'Masuk Warga')

@section('content')
<div class="auth-split-card">
    <aside class="auth-split-panel auth-split-brand">
        <div>
            <div class="auth-brand-mark">
                <img src="{{ asset('img/Gemini_Generated_Image_cmvdd5cmvdd5cmvd-removebg-preview.png') }}" alt="Logo SIGAP">
            </div>
            <div class="auth-brand-title">SIGAP</div>
            <div class="auth-brand-subtitle">Sistem Informasi Gerak Aduan Publik</div>

            <ul class="auth-brand-list">
                <li>Laporkan masalah lingkungan desa dengan cepat dan terdokumentasi.</li>
                <li>Pelacakan status pengaduan dari verifikasi sampai pengerjaan lapangan.</li>
                <li>Transparansi layanan desa yang lebih responsif dan akuntabel.</li>
            </ul>
        </div>

        <a href="{{ url('/') }}" class="auth-back-link">← Kembali ke Halaman Utama</a>
    </aside>

    <section class="auth-split-panel auth-split-form">
        <div class="auth-role-badge">Portal Warga / Pelapor</div>
        <h1 class="auth-role-title">Masuk sebagai Warga</h1>
        <p class="auth-role-description">
            Ajukan laporan pengaduan lingkungan desa, pantau status verifikasi hingga pengerjaan lapangan, dan beri ulasan hasil layanan.
        </p>

        <form action="{{ route('login', ['role' => 'user']) }}" method="POST" class="auth-form">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required autofocus>
                @error('email')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="auth-btn-primary">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk
            </button>
        </form>

        <div class="auth-divider">Akses</div>
        <div class="auth-form-links text-center">
            <a href="{{ route('password.request') }}">Lupa password?</a>
            <span class="mx-2 text-slate-300">|</span>
            <a href="{{ route('register') }}">Daftar akun</a>
        </div>
    </section>
</div>
@endsection
