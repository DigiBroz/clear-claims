<section class="bg-white py-24">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-warm-bg to-peach-300/40 px-8 py-16 text-center sm:px-16">
            <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-brand-400/20 blur-3xl" data-blob-drift></div>
            <div class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-growth-400/25 blur-3xl" data-blob-drift></div>
            <div class="relative">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
