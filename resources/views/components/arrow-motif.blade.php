@props(['class' => 'h-32 w-full'])

@php
    $uid = \Illuminate\Support\Str::random(8);
    $gradientId = 'arrow-gradient-' . $uid;
    $fillId = 'arrow-fill-' . $uid;
    $pathD = 'M10 110 C 80 110, 100 70, 160 70 S 240 30, 300 30 L 355 30';
@endphp

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 400 130" fill="none" xmlns="http://www.w3.org/2000/svg" data-arrow-motif>
    <defs>
        <linearGradient id="{{ $gradientId }}" x1="0" y1="0" x2="400" y2="0" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#2f7abf" />
            <stop offset="100%" stop-color="#29a467" />
        </linearGradient>
        <linearGradient id="{{ $fillId }}" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#29a467" stop-opacity="0.3" />
            <stop offset="100%" stop-color="#29a467" stop-opacity="0" />
        </linearGradient>
    </defs>

    <path data-arrow-fill d="{{ $pathD }} L 355 130 L 10 130 Z" fill="url(#{{ $fillId }})" />

    <path data-arrow-glow d="{{ $pathD }}" stroke="url(#{{ $gradientId }})" stroke-width="8" stroke-linecap="round" fill="none" style="filter: blur(5px)" />

    <path data-arrow-path d="{{ $pathD }}" stroke="url(#{{ $gradientId }})" stroke-width="4" stroke-linecap="round" fill="none" />

    <circle data-arrow-dot cx="10" cy="110" r="5" fill="#2f7abf" />
    <circle data-arrow-dot cx="160" cy="70" r="5" fill="#4ec98a" />

    <circle data-arrow-shimmer r="4.5" fill="#eafff5" style="offset-path: path('{{ $pathD }}')" />

    <circle data-arrow-pulse cx="362" cy="30" r="10" fill="none" stroke="#29a467" stroke-width="2" style="transform-origin: 362px 30px;" />

    <path data-arrow-head d="M347 18 L 372 30 L 347 42 Z" fill="#29a467" style="transform-origin: 362px 30px;" />
</svg>
