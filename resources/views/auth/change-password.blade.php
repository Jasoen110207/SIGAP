@extends('layouts.auth')

@section('title', 'Ganti Password')

@section('content')
<div class="card auth-card">
    <div class="card-body">
        <h4 class="fw-bold mb-1">Ganti Password</h4>
        <p class="text-muted small mb-4">Password Anda sudah melewati masa berlaku demo. Silakan buat password baru yang aman.</p>

        <form action="{{ route('password.change.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password"
                       class="form-control @error('current_password') is-invalid @enderror" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                <div class="form-text">Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol.</div>
                <div id="password-strength" class="small mt-2 text-muted">Kekuatan password: belum dicek.</div>
                <ul class="small mt-2 mb-0 ps-3 text-muted">
                    <li data-rule="length">Panjang minimal 8 karakter</li>
                    <li data-rule="uppercase">Minimal 1 huruf besar</li>
                    <li data-rule="number">Minimal 1 angka</li>
                    <li data-rule="symbol">Minimal 1 simbol</li>
                </ul>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" required>
                <div id="password-match" class="small mt-2 text-muted">Belum ada konfirmasi.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-key me-1"></i>Ubah Password
            </button>
        </form>
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
                const label = element.textContent.replace(/:.*/, '').trim();
                element.textContent = `${label}: ${passed ? '✓' : '✗'}`;
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
