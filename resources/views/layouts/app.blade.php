<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-700 antialiased">
    {{-- Navigation --}}
    <nav class="fixed top-0 z-50 w-full border-b border-warm-border bg-warm-surface/90 backdrop-blur-xl" x-data="{ open: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="py-2">
                    <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-32 w-auto">
                </a>

                <div class="hidden items-center gap-8 md:flex">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('home') ? 'text-brand-600' : '' }}">Home</a>
                    <a href="{{ route('services') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('services') ? 'text-brand-600' : '' }}">Services</a>
                    <a href="{{ route('pricing') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('pricing') ? 'text-brand-600' : '' }}">Pricing Model</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('about') ? 'text-brand-600' : '' }}">About</a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">Contact Us</a>
                </div>

                <button @click="open = !open" class="md:hidden text-slate-600 hover:text-brand-600">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak x-transition class="border-t border-slate-200 bg-white md:hidden">
            <div class="space-y-1 px-4 py-4">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">Home</a>
                <a href="{{ route('services') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">Services</a>
                <a href="{{ route('pricing') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">Pricing Model</a>
                <a href="{{ route('about') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">About</a>
                <a href="{{ route('contact') }}" class="block rounded-full bg-brand-600 px-3 py-2 text-center text-base font-semibold text-white">Contact Us</a>
            </div>
        </div>
    </nav>

    <main class="pt-24">
        @yield('content')
    </main>

    <footer class="border-t border-warm-border bg-warm-bg">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-12 md:grid-cols-4">
                <div class="md:col-span-1">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-36 w-auto">
                    </a>
                    <p class="mt-4 text-sm leading-relaxed text-slate-500">Medical billing and practice support solutions that help South African healthcare providers get paid faster and spend less time on admin.</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-800">Services</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('services') }}#submission" class="text-sm text-slate-500 transition hover:text-brand-600">Claims Submission</a></li>
                        <li><a href="{{ route('services') }}#followups" class="text-sm text-slate-500 transition hover:text-brand-600">Follow-Ups and Collections</a></li>
                        <li><a href="{{ route('services') }}#reconciliation" class="text-sm text-slate-500 transition hover:text-brand-600">Payment Reconciliation</a></li>
                        <li><a href="{{ route('services') }}#reporting" class="text-sm text-slate-500 transition hover:text-brand-600">Financial Reporting</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-800">Company</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('pricing') }}" class="text-sm text-slate-500 transition hover:text-brand-600">Pricing Model</a></li>
                        <li><a href="{{ route('about') }}" class="text-sm text-slate-500 transition hover:text-brand-600">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-slate-500 transition hover:text-brand-600">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-800">Get in Touch</h3>
                    <ul class="mt-4 space-y-3">
                        <li class="text-sm text-slate-500">071 339 5866</li>
                        <li class="text-sm text-slate-500">info@clearclaims.health</li>
                        <li class="text-sm text-slate-500">South Africa</li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-slate-200 pt-8 text-center">
                <p class="text-sm text-slate-400">&copy; {{ date('Y') }} ClearClaims Health Accounts (Pty) Ltd. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
