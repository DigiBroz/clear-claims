@props(['icon' => 'check'])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 rounded-2xl border border-warm-border bg-warm-surface px-4 py-3 shadow-lg shadow-brand-900/10']) }} data-float-chip>
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-growth-500/15 text-growth-600">
        @if($icon === 'check')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        @endif
    </span>
    <span class="text-sm font-semibold text-warm-text">{{ $slot }}</span>
</div>
