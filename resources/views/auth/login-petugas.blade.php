@extends('layouts.auth')

@section('title', 'Masuk Petugas')

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
                <li>Kelola antrean laporan sesuai kategori tugas dan prioritas kerja.</li>
                <li>Proses hasil pengerjaan di lapangan dengan dokumentasi yang terstruktur.</li>
                <li>Kecepatan tindak lanjut menjaga pelayanan publik tetap akuntabel.</li>
            </ul>
        </div>

        <a href="{{ url('/') }}" class="auth-back-link">← Kembali ke Halaman Utama</a>
    </aside>

    <section class="auth-split-panel auth-split-form">
        <div class="auth-role-badge">Portal Petugas Lapangan</div>
        <h1 class="auth-role-title">Masuk sebagai Petugas</h1>
        <p class="auth-role-description">
            Kelola antrean laporan sesuai kategori tugas, lakukan tindak lanjut di lapangan, dan unggah dokumentasi foto hasil pengerjaan.
        </p>

        <form action="{{ route('petugas.login') }}" method="POST" class="auth-form">
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
            <div class="mb-3">
                <label for="login_token" class="form-label">Token Login Petugas</label>
                <input type="text" id="login_token" name="login_token" value="{{ old('login_token') }}"
                       class="form-control @error('login_token') is-invalid @enderror" required>
                @error('login_token')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="auth-btn-primary">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk
            </button>
        </form>
    </section>
</div>
@endsection
