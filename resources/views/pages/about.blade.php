@extends('layouts.app')

@section('content')
    <x-warm-hero>
        <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Medical Billing Support That Lets You Focus on Patients</h1>
    </x-warm-hero>

    <section class="py-24">
        <div class="mx-auto max-w-4xl space-y-12 px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Who We Are</h2>
                <p class="mt-4 text-slate-600">ClearClaims Health Accounts (Pty) Ltd is a medical billing and practice support company based in South Africa. We work with healthcare providers to improve cash flow, reduce administrative burden, and streamline the day to day work of managing medical claims.</p>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Our Mission</h2>
                <p class="mt-4 text-slate-600">To provide accurate, efficient, and transparent medical billing solutions that allow healthcare professionals to focus on patient care.</p>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Who We Serve</h2>
                <p class="mt-4 text-slate-600">We support general practitioners, specialists, and allied health practices who want their billing handled properly without hiring and managing an in-house billing team. Whether you are a solo practitioner or a multi-doctor practice, we adapt our process to fit how your practice already runs.</p>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Our Commitment</h2>
                <ul class="mt-4 space-y-3 text-slate-600">
                    <li><strong class="text-brand-900">Accuracy.</strong> We check every claim before it reaches a medical aid, so your practice sees fewer rejections.</li>
                    <li><strong class="text-brand-900">Efficiency.</strong> Claims move through submission, follow-up, and reconciliation without unnecessary delay.</li>
                    <li><strong class="text-brand-900">Confidentiality.</strong> Patient and practice information is handled with the discretion healthcare data demands.</li>
                    <li><strong class="text-brand-900">Professional service.</strong> Your practice is represented professionally in every interaction with medical aids.</li>
                </ul>
            </div>
        </div>
    </section>

    <x-warm-cta>
        <h2 class="text-3xl font-bold text-brand-900">Want to Talk Through Your Practice's Billing?</h2>
        <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get in Touch</a>
    </x-warm-cta>
@endsection
