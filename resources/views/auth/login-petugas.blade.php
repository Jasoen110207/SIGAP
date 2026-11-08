@extends('layouts.app')

@section('title', 'Masuk Petugas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card stat-card">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Masuk sebagai Petugas</h4>
                <p class="text-muted small mb-4">Gunakan akun petugas dan token login yang valid.</p>

                <form action="{{ route('petugas.login') }}" method="POST">
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
                    <div class="mb-3">
                        <label for="login_token" class="form-label">Token Login Petugas</label>
                        <input type="text" id="login_token" name="login_token" value="{{ old('login_token') }}"
                               class="form-control @error('login_token') is-invalid @enderror" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-right-to-bracket me-1"></i>Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
