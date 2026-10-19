@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card stat-card">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Masuk ke SIGAP</h4>
                <p class="text-muted small mb-4">Gunakan akun warga, admin, atau petugas.</p>

                <form action="{{ route('login.attempt') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-right-to-bracket me-1"></i>Masuk
                    </button>
                </form>

                <hr>
                <p class="text-center small mb-0">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai warga</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
