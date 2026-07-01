@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <x-warm-hero>
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div>
                <h1 class="max-w-xl text-4xl font-bold text-brand-900 sm:text-5xl">Get Paid Faster. Do Less Admin. Stay Focused on Patients.</h1>
                <p class="mt-6 max-w-xl text-lg text-slate-600">ClearClaims Health Accounts handles medical billing and practice support so your team can spend less time chasing medical aids and more time treating patients.</p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-full border border-warm-border bg-warm-surface px-8 py-3.5 text-base font-semibold text-slate-700 transition hover:border-brand-400 hover:text-brand-700">See Our Pricing Model</a>
                </div>
            </div>
            <div class="relative">
                <x-warm-chart-card>
                    <p class="text-xs font-semibold uppercase tracking-wider text-warm-text/70">Our Pricing Model</p>
                    <p class="mt-1 text-lg font-semibold text-brand-900">Percentage of Collections</p>
                    <x-arrow-motif class="mt-4 h-20 w-full" />
                </x-warm-chart-card>
                <x-warm-floating-chip class="absolute -bottom-6 -left-6 hidden sm:inline-flex">
                    You only pay when we collect
                </x-warm-floating-chip>
            </div>
        </div>
    </x-warm-hero>

    {{-- Services overview --}}
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Everything Your Practice Needs to Get Paid Properly</h2>
            <p class="mt-4 max-w-2xl text-slate-600">From claims submission to payment reconciliation, we manage the full billing cycle for your practice.</p>

            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Medical Claims Submission and Processing</h3>
                    <p class="mt-2 text-sm text-slate-600">We prepare and submit claims accurately and on time, checking coding and documentation before anything reaches a medical aid.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Medical Aid Follow-Ups and Collections</h3>
                    <p class="mt-2 text-sm text-slate-600">We follow up directly with medical aids on outstanding and disputed claims until they are resolved.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Payment Reconciliation and Allocation</h3>
                    <p class="mt-2 text-sm text-slate-600">Every payment gets matched against the original claim and allocated to the correct patient account.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Patient Account Management</h3>
                    <p class="mt-2 text-sm text-slate-600">We keep patient billing accounts up to date, so your front desk is not stuck managing billing queries.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Practice Financial Reporting</h3>
                    <p class="mt-2 text-sm text-slate-600">Regular, easy to understand reports on submissions, payments, and outstanding claims.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Onboarding Support for New Practices</h3>
                    <p class="mt-2 text-sm text-slate-600">We handle the setup, from mapping existing patient data to getting your team comfortable with the new process.</p>
                </div>
            </div>

            <a href="{{ route('services') }}" class="mt-10 inline-flex items-center font-semibold text-brand-600 hover:text-brand-700">See every service in detail &rarr;</a>
        </div>
    </section>

    {{-- Pricing teaser --}}
    <section class="bg-warm-bg py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2.5rem] bg-brand-900 px-8 py-16 text-center text-white sm:px-16">
                <h2 class="text-3xl font-bold">We Only Get Paid When You Get Paid</h2>
                <p class="mt-6 text-lg text-brand-100">ClearClaims works on a percentage of collections model. Our fee is calculated on the money medical aids actually pay out to your practice, not on the claims we submit. If a claim is rejected or never paid, we do not charge for it. That keeps our incentives lined up with yours from the first submission to the final reconciled payment.</p>
                <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-full bg-growth-500 px-8 py-3.5 text-base font-semibold text-white transition hover:bg-growth-600">See How Our Pricing Works</a>
                <div class="mt-10 flex justify-center">
                    <div class="rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur-sm">
                        <x-arrow-motif class="h-20 w-full max-w-md" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">How It Works</h2>
            <div class="mt-12 space-y-8">
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">1</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">Submit Patient and Treatment Details</h3>
                        <p class="mt-1 text-slate-600">Your practice sends us the information we need for each consultation or procedure, in whatever format works for your existing systems.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">2</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">We Process and Submit Claims Accurately</h3>
                        <p class="mt-1 text-slate-600">Our team checks every claim for coding and documentation issues before it goes to the medical aid, reducing the chance of a rejection.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">3</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">We Follow Up With Medical Aids</h3>
                        <p class="mt-1 text-slate-600">Outstanding and disputed claims get followed up directly with the relevant medical aid until they are resolved, not left to sit in a queue.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">4</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">Payments Are Tracked and Reconciled</h3>
                        <p class="mt-1 text-slate-600">Every payment that comes in gets matched against the original claim and allocated to the correct patient account.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">5</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">You Receive Regular Updates and Reports</h3>
                        <p class="mt-1 text-slate-600">Clear, regular reporting on what has been submitted, what has been paid, and what still needs attention, so you always know where your practice's income stands.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Commitment values --}}
    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Our Commitment</h2>
            <div class="mt-12 grid gap-8 md:grid-cols-4">
                <div>
                    <h3 class="font-semibold text-brand-900">Accuracy</h3>
                    <p class="mt-2 text-sm text-slate-600">Every claim is checked before submission, so your practice sees fewer rejections and less rework.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Efficiency</h3>
                    <p class="mt-2 text-sm text-slate-600">Claims move through submission, follow-up, and reconciliation without unnecessary delay.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Confidentiality</h3>
                    <p class="mt-2 text-sm text-slate-600">Patient and practice information is handled with the care and discretion healthcare data demands.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Professional Service</h3>
                    <p class="mt-2 text-sm text-slate-600">Your practice and your patients are represented professionally in every interaction with medical aids.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Closing CTA --}}
    <x-warm-cta>
        <h2 class="text-3xl font-bold text-brand-900">Ready to Spend Less Time on Billing?</h2>
        <p class="mt-4 text-slate-700">Tell us about your practice and we will show you what ClearClaims can take off your plate.</p>
        <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
    </x-warm-cta>
@endsection
