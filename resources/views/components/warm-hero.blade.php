<section class="relative overflow-hidden bg-gradient-to-b from-warm-bg to-white">
    <div class="pointer-events-none absolute -left-32 -top-40 h-96 w-96 rounded-full bg-brand-400/30 blur-3xl" data-blob-drift></div>
    <div class="pointer-events-none absolute -right-24 -top-52 h-[28rem] w-[28rem] rounded-full bg-peach-500/40 blur-3xl" data-blob-drift></div>
    <div class="pointer-events-none absolute bottom-10 left-1/3 h-64 w-64 rounded-full bg-growth-400/20 blur-3xl" data-blob-drift></div>

    <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>

    <svg class="relative block w-full text-white" viewBox="0 0 1440 60" preserveAspectRatio="none" style="height: 48px;" aria-hidden="true">
        <path d="M0 24 C 240 54, 480 0, 720 18 C 960 36, 1200 6, 1440 30 L 1440 60 L 0 60 Z" fill="currentColor"/>
    </svg>
</section>
