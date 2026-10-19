@extends('layouts.app')

@section('title', 'Dashboard Warga')

@section('content')
<div class="row g-4">
    {{-- Form Lapor --}}
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fa-solid fa-pen-to-square me-1"></i>Buat Laporan Baru
            </div>
            <div class="card-body">
                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Laporan</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select id="category_id" name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">— Pilih kategori —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Jelaskan masalah secara rinci..." required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Alamat / Lokasi</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}"
                               class="form-control @error('location') is-invalid @enderror"
                               placeholder="Contoh: Jl. Melati No. 5 RT 02/RW 03" required>
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                        <button type="button" id="btn-geo" class="btn btn-sm btn-outline-secondary mt-2">
                            <i class="fa-solid fa-location-crosshairs me-1"></i>Gunakan lokasi saya
                        </button>
                        <small id="geo-status" class="text-muted ms-1"></small>
                    </div>
                    <div class="mb-3">
                        <label for="photo_before" class="form-label">Foto Bukti Awal</label>
                        <input type="file" id="photo_before" name="photo_before" accept="image/*"
                               class="form-control @error('photo_before') is-invalid @enderror" required>
                        <div class="form-text">JPG/PNG/WEBP, maksimal 4 MB.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-paper-plane me-1"></i>Kirim Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Laporanku --}}
    <div class="col-lg-8">
        <div class="card stat-card">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-list-check me-1 text-primary"></i>Laporanku
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>Laporan</th>
                            <th style="min-width: 170px;">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            @php
                                $stepKeys = ['pending', 'validated', 'in_progress', 'completed'];
                                $stepIndex = array_search($report->status, $stepKeys, true);
                                $progress = $stepIndex === false ? 0 : (($stepIndex + 1) / count($stepKeys)) * 100;
                            @endphp
                            <tr>
                                <td><img src="{{ $report->photo_before_url }}" class="report-thumb" alt="Foto"></td>
                                <td>
                                    <div class="fw-semibold">{{ $report->title }}</div>
                                    <div class="small text-muted">
                                        {{ $report->category->name }} &middot; {{ $report->created_at->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $report->status_badge }}">{{ $report->status_label }}</span>
                                    @if ($report->status !== 'rejected')
                                        <div class="progress mt-2" style="height: 6px;" title="{{ $report->status_label }}">
                                            <div class="progress-bar bg-{{ $report->status_badge }}" style="width: {{ $progress }}%"></div>
                                        </div>
                                    @endif
                                    @if ($report->status === 'completed' && $report->rating)
                                        <div class="mt-1 small">@include('partials.stars', ['value' => $report->rating])</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal" data-bs-target="#detail-{{ $report->id }}">
                                        <i class="fa-solid fa-eye me-1"></i>Detail
                                    </button>
                                    @if ($report->status === 'completed' && $report->rating === null)
                                        <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal" data-bs-target="#detail-{{ $report->id }}">
                                            <i class="fa-solid fa-star me-1"></i>Beri Rating
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fa-regular fa-folder-open fa-2x d-block mb-2"></i>
                                    Anda belum membuat laporan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($reports->hasPages())
                <div class="card-footer bg-white">
                    {{ $reports->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Detail + Tracking + Rating --}}
@foreach ($reports as $report)
    <div class="modal fade" id="detail-{{ $report->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">{{ $report->title }}</h5>
                        <span class="badge text-bg-{{ $report->status_badge }}">{{ $report->status_label }}</span>
                        <span class="badge text-bg-secondary">{{ $report->category->name }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $report->location }}</p>
                    <p class="text-muted small">Dikirim {{ $report->created_at->format('d M Y H:i') }}</p>
                    <p>{{ $report->description }}</p>

                    @if ($report->status === 'rejected')
                        <div class="alert alert-danger">
                            <strong>Alasan penolakan:</strong><br>{{ $report->rejection_note }}
                        </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="fw-semibold small mb-1">Foto Bukti Awal</div>
                            <img src="{{ $report->photo_before_url }}" class="photo-modal" alt="Foto sebelum">
                        </div>
                        @if ($report->photo_after)
                            <div class="col-md-6">
                                <div class="fw-semibold small mb-1">Foto Bukti Selesai</div>
                                <img src="{{ $report->photo_after_url }}" class="photo-modal" alt="Foto sesudah">
                            </div>
                        @endif
                    </div>

                    @if ($report->status === 'completed')
                        <div class="border rounded p-3 bg-light mb-3">
                            <div><strong>Eksekutor:</strong> {{ $report->executor_name }}</div>
                            <div><strong>Catatan perbaikan:</strong> {{ $report->executor_note }}</div>
                        </div>

                        @if ($report->rating === null)
                            <form action="{{ route('reports.rate', $report) }}" method="POST">
                                @csrf
                                <h6 class="fw-bold">Beri Penilaian</h6>
                                <div class="btn-group mb-3" role="group" aria-label="Rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input type="radio" class="btn-check" name="rating" id="rate-{{ $report->id }}-{{ $i }}"
                                               value="{{ $i }}" autocomplete="off" required>
                                        <label class="btn btn-outline-warning" for="rate-{{ $report->id }}-{{ $i }}">
                                            {{ $i }} <i class="fa-solid fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="review-{{ $report->id }}">Ulasan (opsional)</label>
                                    <textarea id="review-{{ $report->id }}" name="review" rows="3" class="form-control"
                                              maxlength="1000" placeholder="Bagaimana penanganan laporan Anda?"></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa-solid fa-star me-1"></i>Kirim Penilaian
                                </button>
                            </form>
                        @else
                            <div>
                                <h6 class="fw-bold">Penilaian Anda</h6>
                                @include('partials.stars', ['value' => $report->rating])
                                @if ($report->review)
                                    <p class="mt-2 mb-0 fst-italic">"{{ $report->review }}"</p>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    document.getElementById('btn-geo').addEventListener('click', function () {
        const status = document.getElementById('geo-status');

        if (!navigator.geolocation) {
            status.textContent = 'Browser tidak mendukung geolokasi.';
            return;
        }

        status.textContent = 'Mencari lokasi...';
        navigator.geolocation.getCurrentPosition(function (pos) {
            document.getElementById('latitude').value = pos.coords.latitude.toFixed(8);
            document.getElementById('longitude').value = pos.coords.longitude.toFixed(8);
            status.textContent = 'Koordinat tersimpan.';
        }, function () {
            status.textContent = 'Gagal mengambil lokasi.';
        });
    });
</script>
@endpush
