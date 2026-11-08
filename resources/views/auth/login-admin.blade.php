@extends('layouts.app')

@section('title', 'Masuk Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card stat-card">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Masuk sebagai Admin</h4>
                <p class="text-muted small mb-4">Akses dashboard administrasi kelurahan.</p>

                <form action="{{ route('admin.login') }}" method="POST">
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
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-right-to-bracket me-1"></i>Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
