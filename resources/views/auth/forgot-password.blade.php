@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card stat-card">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Lupa Password</h4>
                <p class="text-muted small mb-4">Masukkan email akun Anda. Kami akan mengirimkan link untuk mengatur ulang password.</p>

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-paper-plane me-1"></i>Kirim Link Reset
                    </button>
                </form>

                <hr>
                <p class="text-center small mb-0">
                    Ingat password Anda? <a href="{{ route('login') }}">Kembali ke login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
