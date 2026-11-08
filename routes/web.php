<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleLoginController;

// Publik
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [RoleLoginController::class, 'create'])->defaults('role', 'user')->name('login');
    Route::post('/login', [RoleLoginController::class, 'store'])->defaults('role', 'user')->name('login.attempt');

    Route::get('/petugas/login', [RoleLoginController::class, 'create'])->defaults('role', 'petugas')->name('petugas.login');
    Route::post('/petugas/login', [RoleLoginController::class, 'store'])->defaults('role', 'petugas')->name('petugas.login.attempt');

    Route::get('/admin/login', [RoleLoginController::class, 'create'])->defaults('role', 'admin')->name('admin.login');
    Route::post('/admin/login', [RoleLoginController::class, 'store'])->defaults('role', 'admin')->name('admin.login.attempt');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware(['auth', EnsureUserIsActive::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.store');

    // Dashboard (otomatis sesuai role)
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');

    // Warga
    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::get('/warga/profil', function () {
            return view('dashboard.warga-profil');
        })->name('warga.profile');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::post('/reports/{report}/rate', [ReportController::class, 'rate'])->name('reports.rate');
    });

    // Admin Kelurahan
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::patch('/reports/{report}/verify', [ReportController::class, 'verify'])->name('reports.verify');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
            // Kelola akun petugas
            Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
            Route::get('/petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
            Route::post('/petugas', [PetugasController::class, 'store'])->name('petugas.store');
            Route::get('/petugas/{petugas}/edit', [PetugasController::class, 'edit'])->name('petugas.edit');
            Route::put('/petugas/{petugas}', [PetugasController::class, 'update'])->name('petugas.update');
            Route::patch('/petugas/{petugas}/toggle', [PetugasController::class, 'toggle'])->name('petugas.toggle');
        });
    });

    // Petugas Lapangan
    Route::middleware(['auth', 'role:petugas'])->group(function () {
        Route::patch('/reports/{report}/start', [ReportController::class, 'startWork'])->name('reports.start');
        Route::patch('/reports/{report}/release', [ReportController::class, 'releaseWork'])->name('reports.release');
        Route::post('/reports/{report}/complete', [ReportController::class, 'completeWork'])->name('reports.complete');
    });
});
