@extends('layouts.warga')

@section('title', 'Profil Saya')

@section('content')
<div class="card stat-card mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="fa-solid fa-user me-1 text-primary"></i>Profil Saya
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3 text-center">
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center mx-auto"
                     style="width:72px;height:72px;font-size:1.8rem;font-weight:700;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'W', 0, 1)) }}
                </div>
            </div>
            <div class="col-md-9">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Nama Lengkap</label>
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Email</label>
                        <div class="fw-semibold">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Nomor Telepon</label>
                        <div class="fw-semibold">{{ auth()->user()->phone ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Role</label>
                        <div class="fw-semibold">Warga</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card stat-card">
    <div class="card-header bg-white fw-bold">
        <i class="fa-solid fa-key me-1 text-primary"></i>Password Akun
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label text-muted small mb-1">Password Saat Ini</label>
            <div class="fw-semibold">••••••••</div>
        </div>

        <form action="{{ route('password.change.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label">Password Saat Ini</label>
                <div class="input-group">
                    <input type="password" id="current_password" name="current_password"
                           class="form-control @error('current_password') is-invalid @enderror" required>
                    <button type="button" class="btn btn-outline-secondary" data-toggle-password="current_password" aria-label="Lihat password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <div class="input-group">
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" required>
                    <button type="button" class="btn btn-outline-secondary" data-toggle-password="password" aria-label="Lihat password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="form-text">Minimal 8 karakter, kombinasi huruf besar, angka, dan simbol.</div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" data-toggle-password="password_confirmation" aria-label="Lihat password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square me-1"></i>Update Password
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll('[data-toggle-password]');

        toggleButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const inputId = button.getAttribute('data-toggle-password');
                const input = document.getElementById(inputId);
                const icon = button.querySelector('i');

                if (!input || !icon) {
                    return;
                }

                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
                button.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Lihat password');
            });
        });
    });
</script>
@endpush
