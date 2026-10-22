<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::middleware(['auth', EnsureUserIsActive::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (otomatis sesuai role)
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');

    // Warga
    Route::middleware(['auth', 'role:user'])->group(function () {
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
