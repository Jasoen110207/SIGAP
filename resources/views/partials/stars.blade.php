@for ($i = 1; $i <= 5; $i++)
    <i class="fa-solid fa-star {{ $i <= round($value ?? 0) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
@endfor
