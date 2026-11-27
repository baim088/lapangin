@props([
    'src',
    'alt' => '',
    'eager' => false,
    'fallback' => null,
])

@php
    $fallbackUrl = $fallback ?? asset('images/courts/futsal.jpg');
@endphp

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    @if($eager)
        fetchpriority="high"
        decoding="async"
    @else
        loading="lazy"
        decoding="async"
    @endif
    onerror="this.onerror=null; this.src='{{ $fallbackUrl }}';"
    {{ $attributes }}
>
