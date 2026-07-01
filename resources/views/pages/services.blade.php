@extends('layouts.app')

@section('content')
    <x-warm-hero>
        <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Our Services</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-600">Everything your practice needs to submit, chase, reconcile, and report on medical claims, handled by one team.</p>
    </x-warm-hero>

    <section class="py-24">
        <div class="mx-auto max-w-5xl space-y-16 px-4 sm:px-6 lg:px-8">
            <div id="submission">
                <h2 class="text-2xl font-bold text-brand-900">Medical Claims Submission and Processing</h2>
                <p class="mt-4 text-slate-600">We prepare and submit your medical claims accurately and on time, checking coding and documentation before anything reaches a medical aid. That reduces the number of claims that come back rejected or queried, which means your practice gets paid faster and with less back and forth.</p>
            </div>
            <div id="followups">
                <h2 class="text-2xl font-bold text-brand-900">Medical Aid Follow-Ups and Collections</h2>
                <p class="mt-4 text-slate-600">Submitting a claim is only half the job. We follow up directly with medical aids on outstanding, delayed, or disputed claims until they are resolved, so unpaid claims do not quietly disappear into an inbox.</p>
            </div>
            <div id="reconciliation">
                <h2 class="text-2xl font-bold text-brand-900">Payment Reconciliation and Allocation</h2>
                <p class="mt-4 text-slate-600">When a medical aid pays out, we match that payment against the original claim and allocate it correctly to the patient's account. This keeps your practice's financial records accurate and gives you a true picture of what has actually been collected.</p>
            </div>
            <div id="accounts">
                <h2 class="text-2xl font-bold text-brand-900">Patient Account Management</h2>
                <p class="mt-4 text-slate-600">We keep patient billing accounts up to date, tracking balances, co-payments, and outstanding amounts, so your front desk team is not stuck managing billing queries on top of everything else.</p>
            </div>
            <div id="reporting">
                <h2 class="text-2xl font-bold text-brand-900">Practice Financial Reporting</h2>
                <p class="mt-4 text-slate-600">You receive regular, easy to understand reports on submissions, payments, and outstanding claims, giving you visibility into your practice's income without having to dig through statements yourself.</p>
            </div>
            <div id="onboarding">
                <h2 class="text-2xl font-bold text-brand-900">Onboarding Support for New Practices</h2>
                <p class="mt-4 text-slate-600">Moving your billing to ClearClaims does not mean starting from scratch. We handle the setup, from mapping your existing patient and claims data to getting your team comfortable with the new process, with minimal disruption to your practice.</p>
            </div>
        </div>
    </section>

    <x-warm-cta>
        <h2 class="text-3xl font-bold text-brand-900">Curious What This Costs?</h2>
        <p class="mt-4 text-slate-700">Our pricing is built so we only earn when your practice actually gets paid.</p>
        <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">See Our Pricing Model</a>
    </x-warm-cta>
@endsection
