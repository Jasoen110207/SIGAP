@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1">Daftar Akun Warga</h4>
                <p class="text-muted small mb-4">Buat akun untuk mulai melaporkan masalah di lingkungan Anda.</p>

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror" required autofocus>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="phone" class="form-label">No. HP</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="form-control @error('phone') is-invalid @enderror" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea id="address" name="address" rows="3"
                                  class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <div class="form-text">Minimal 8 karakter, harus ada huruf besar, angka, dan simbol.</div>
                            <div id="password-strength" class="small mt-2 text-muted">Kekuatan password: belum dicek.</div>
                            <ul class="small mt-2 mb-0 ps-3 text-muted">
                                <li data-rule="length">Panjang minimal 8 karakter</li>
                                <li data-rule="uppercase">Minimal 1 huruf besar</li>
                                <li data-rule="number">Minimal 1 angka</li>
                                <li data-rule="symbol">Minimal 1 simbol</li>
                            </ul>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" required>
                            <div id="password-match" class="small mt-2 text-muted">Belum ada konfirmasi.</div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-user-plus me-1"></i>Daftar
                    </button>
                </form>

                <hr>
                <p class="text-center small mb-0">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const strengthText = document.getElementById('password-strength');
        const matchText = document.getElementById('password-match');

        function updatePasswordRules(value) {
            const rules = {
                length: value.length >= 8,
                uppercase: /[A-Z]/.test(value),
                number: /\d/.test(value),
                symbol: /[^A-Za-z0-9]/.test(value),
            };

            const ruleElements = document.querySelectorAll('[data-rule]');
            ruleElements.forEach((element) => {
                const key = element.dataset.rule;
                const passed = rules[key];
                element.classList.toggle('text-success', passed);
                element.classList.toggle('text-danger', !passed && value.length > 0);
                element.classList.toggle('text-muted', !passed && value.length === 0);
                element.innerHTML = `${element.textContent.replace(/:.*/, '')}: ${passed ? '✓' : '✗'}`;
            });

            const passedCount = Object.values(rules).filter(Boolean).length;
            if (!value) {
                strengthText.textContent = 'Kekuatan password: belum dicek.';
                strengthText.className = 'small mt-2 text-muted';
            } else if (passedCount === 4) {
                strengthText.textContent = 'Kekuatan password: kuat';
                strengthText.className = 'small mt-2 text-success';
            } else if (passedCount >= 2) {
                strengthText.textContent = 'Kekuatan password: cukup';
                strengthText.className = 'small mt-2 text-warning';
            } else {
                strengthText.textContent = 'Kekuatan password: lemah';
                strengthText.className = 'small mt-2 text-danger';
            }
        }

        function updateConfirmationState() {
            const value = passwordConfirmationInput.value;
            if (!value) {
                matchText.textContent = 'Belum ada konfirmasi.';
                matchText.className = 'small mt-2 text-muted';
                return;
            }

            if (value === passwordInput.value) {
                matchText.textContent = 'Password konfirmasi cocok.';
                matchText.className = 'small mt-2 text-success';
            } else {
                matchText.textContent = 'Password konfirmasi tidak cocok.';
                matchText.className = 'small mt-2 text-danger';
            }
        }

        passwordInput.addEventListener('input', function () {
            updatePasswordRules(this.value);
            updateConfirmationState();
        });

        passwordConfirmationInput.addEventListener('input', updateConfirmationState);
        updatePasswordRules(passwordInput.value);
        updateConfirmationState();
    });
</script>
@endpush
