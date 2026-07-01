@props(['class' => 'h-32 w-full'])

@php
    $gradientId = 'arrow-gradient-' . \Illuminate\Support\Str::random(8);
@endphp

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 400 120" fill="none" xmlns="http://www.w3.org/2000/svg" data-arrow-motif>
    <path
        data-arrow-path
        d="M10 100 C 80 100, 100 60, 160 60 S 240 20, 300 20 L 370 20"
        stroke="url(#{{ $gradientId }})"
        stroke-width="4"
        stroke-linecap="round"
        fill="none"
    />
    <path d="M355 8 L 380 20 L 355 32 Z" fill="#29a467" data-arrow-head />
    <defs>
        <linearGradient id="{{ $gradientId }}" x1="0" y1="0" x2="400" y2="0" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#2f7abf" />
            <stop offset="100%" stop-color="#29a467" />
        </linearGradient>
    </defs>
</svg>
