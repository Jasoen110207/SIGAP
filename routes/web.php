<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RoleMiddleware;
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
    Route::middleware(RoleMiddleware::class . ':user')->group(function () {
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::post('/reports/{report}/rate', [ReportController::class, 'rate'])->name('reports.rate');
    });

    // Admin Kelurahan
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::patch('/reports/{report}/verify', [ReportController::class, 'verify'])->name('reports.verify');

        // Kelola akun petugas
        Route::get('/admin/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::get('/admin/petugas/create', [PetugasController::class, 'create'])->name('petugas.create');
        Route::post('/admin/petugas', [PetugasController::class, 'store'])->name('petugas.store');
        Route::get('/admin/petugas/{petugas}/edit', [PetugasController::class, 'edit'])->name('petugas.edit');
        Route::put('/admin/petugas/{petugas}', [PetugasController::class, 'update'])->name('petugas.update');
        Route::patch('/admin/petugas/{petugas}/toggle', [PetugasController::class, 'toggle'])->name('petugas.toggle');
    });

    // Petugas Lapangan
    Route::middleware(RoleMiddleware::class . ':petugas')->group(function () {
        Route::patch('/reports/{report}/start', [ReportController::class, 'startWork'])->name('reports.start');
        Route::patch('/reports/{report}/release', [ReportController::class, 'releaseWork'])->name('reports.release');
        Route::post('/reports/{report}/complete', [ReportController::class, 'completeWork'])->name('reports.complete');
    });
});
