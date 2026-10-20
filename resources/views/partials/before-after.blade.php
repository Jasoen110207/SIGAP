<article class="ba">
    <div class="ba-photos">
        <figure class="m-0 bg-white">
            <img src="{{ $report->photo_before_url }}" alt="Kondisi sebelum: {{ $report->title }}" loading="lazy">
            <figcaption class="ba-cap">Sebelum</figcaption>
        </figure>
        <figure class="m-0 bg-white">
            <img src="{{ $report->photo_after_url }}" alt="Kondisi sesudah: {{ $report->title }}" loading="lazy">
            <figcaption class="ba-cap">Sesudah</figcaption>
        </figure>
    </div>
    <div class="ba-body">
        <h3 class="h6 fw-bold mb-1">{{ $report->title }}</h3>
        <p class="text-muted small mb-2">{{ $report->category->name }}, {{ $report->location }}</p>
        @if ($report->executor_note)
            <p class="small mb-2">{{ \Illuminate\Support\Str::limit($report->executor_note, 120) }}</p>
        @endif
        <p class="small text-muted mb-0">
            Dikerjakan oleh {{ $report->executor_name }}, selesai {{ $report->updated_at->locale('id')->translatedFormat('j F Y') }}.
            @if ($report->rating)
                Nilai dari warga: {{ $report->rating }} dari 5.
            @endif
        </p>
    </div>
</article>
