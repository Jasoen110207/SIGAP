@extends('layouts.app')

@section('title', $petugas ? 'Edit Petugas' : 'Tambah Petugas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card stat-card">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fa-solid {{ $petugas ? 'fa-user-pen' : 'fa-user-plus' }} me-1"></i>
                {{ $petugas ? 'Edit Petugas' : 'Tambah Petugas' }}
            </div>
            <div class="card-body">
                <form action="{{ $petugas ? route('admin.petugas.update', $petugas) : route('admin.petugas.store') }}" method="POST">
                    @csrf
                    @if ($petugas)
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $petugas?->name) }}"
                               class="form-control @error('name') is-invalid @enderror" required>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="email" class="form-label">Email (untuk login)</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $petugas?->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="phone" class="form-label">No. HP</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $petugas?->phone) }}"
                                   class="form-control @error('phone') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori Pekerjaan</label>
                        <select id="category_id" name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">— Pilih kategori —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $petugas?->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($petugas)
                            <div class="form-text">
                                Jika kategori diubah, laporan yang sedang dipegang petugas ini dikembalikan ke antrean.
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ $petugas ? 'Password Baru' : 'Password' }}</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   {{ $petugas ? '' : 'required' }}>
                            <div class="form-text">
                                {{ $petugas ? 'Kosongkan jika tidak diganti.' : 'Minimal 8 karakter.' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" {{ $petugas ? '' : 'required' }}>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.petugas.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
