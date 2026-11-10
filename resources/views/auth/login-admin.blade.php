@extends('layouts.app')

@section('title', 'Masuk Admin')

@section('content')
    @push('styles')
        <style>
            .auth-shell {
                min-height: calc(100vh - 220px);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
                background: #f3f4f6;
            }

            .auth-panel {
                width: min(100%, 420px);
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                overflow: hidden;
                box-shadow: none;
            }

            .auth-form-wrap {
                padding: 2rem 1.5rem;
                background: #ffffff;
            }

            .auth-brand {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 3rem;
                height: 3rem;
                border-radius: 0.5rem;
                background: #f3f4f6;
                border: 1px solid #e5e7eb;
                color: #111827;
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }

            .auth-form-wrap h2 {
                font-size: 1.8rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                color: #111827;
                margin-bottom: 0.4rem;
            }

            .auth-form-wrap .subtitle {
                color: #6b7280;
                margin-bottom: 1.5rem;
                line-height: 1.6;
            }

            .auth-form .form-group {
                margin-bottom: 1rem;
            }

            .auth-form label {
                display: block;
                margin-bottom: 0.45rem;
                font-weight: 600;
                color: #374151;
            }

            .auth-form .form-control {
                width: 100%;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                background: #ffffff;
                padding: 0.7rem 0.8rem;
                font-size: 0.95rem;
                color: #111827;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .auth-form .form-control:focus {
                border-color: #9ca3af;
                box-shadow: none;
                outline: 0;
            }

            .auth-form .is-invalid {
                border-color: #dc2626;
            }

            .auth-form .form-check {
                display: flex;
                align-items: center;
                gap: 0.6rem;
                margin: 0.5rem 0 1.2rem;
                color: #4b5563;
                font-size: 0.92rem;
            }

            .auth-form .form-check-input {
                width: 1rem;
                height: 1rem;
                accent-color: #111827;
            }

            .auth-form .btn-primary {
                width: 100%;
                border: 1px solid #111827;
                border-radius: 0.5rem;
                padding: 0.8rem 1rem;
                font-weight: 600;
                background: #111827;
                color: #ffffff;
                box-shadow: none;
            }

            .auth-form .btn-primary:hover {
                background: #1f2937;
                border-color: #1f2937;
            }

            .auth-meta {
                margin-top: 1rem;
                text-align: center;
                color: #6b7280;
                font-size: 0.9rem;
            }

            .auth-meta a {
                color: #111827;
                font-weight: 600;
                text-decoration: none;
            }

            .auth-meta a:hover {
                text-decoration: underline;
            }
        </style>
    @endpush

    <div class="auth-shell">
        <div class="auth-panel">
            <div class="auth-form-wrap">
                <div class="text-center">
                    <div class="auth-brand">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <h2>Masuk Admin</h2>
                    <p class="subtitle">Silakan masuk untuk mengelola sistem.</p>
                </div>

                <form action="{{ route('admin.login.attempt') }}" method="POST" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk
                    </button>
                </form>

                <div class="auth-meta">
                    <a href="{{ route('login') }}">Kembali ke halaman masuk umum</a>
                </div>
            </div>
        </div>
    </div>
@endsection
