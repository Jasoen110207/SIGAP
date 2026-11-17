@extends('layouts.auth')

@section('title', 'Masuk Admin')

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
                <li>Verifikasi laporan masuk agar hanya pengaduan layak yang diproses.</li>
                <li>Pantau kinerja dan distribusi tugas petugas lapangan secara real time.</li>
                <li>Menjaga transparansi layanan desa dan respons cepat terhadap kebutuhan warga.</li>
            </ul>
        </div>

        <a href="{{ url('/') }}" class="auth-back-link">← Kembali ke Halaman Utama</a>
    </aside>

    <section class="auth-split-panel auth-split-form">
        <div class="auth-role-badge">Portal Administrator</div>
        <h1 class="auth-role-title">Masuk sebagai Admin</h1>
        <p class="auth-role-description">
            Verifikasi kelayakan laporan masuk, atur penugasan serta token login petugas, dan pantau laporan capaian kinerja desa.
        </p>

        <form action="{{ route('admin.login.attempt') }}" method="POST" class="auth-form">
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
        <div class="text-center small auth-form-links">
            <a href="{{ route('login') }}">Kembali ke halaman masuk umum</a>
        </div>
    </section>
</div>
@endsection
