@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="card auth-card">
    <div class="card-body">
        <h4 class="fw-bold mb-1">Reset Password</h4>
        <p class="text-muted small mb-4">Buat password baru yang aman untuk akun Anda.</p>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" value="{{ $email }}" class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                <div class="form-text">Minimal 8 karakter.</div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-key me-1"></i>Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection
