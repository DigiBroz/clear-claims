@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Let's Talk About Your Practice's Billing</h1>
            <p class="mt-4 max-w-2xl text-lg text-slate-600">Tell us a bit about your practice and we will get back to you to discuss how ClearClaims can help.</p>
        </div>
    </section>

    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-16 lg:grid-cols-2">
                <div>
                    <h2 class="text-2xl font-bold text-brand-900">Send Us a Message</h2>
                    <p class="mt-2 text-slate-600">Fill out the form below and we will get back to you within 24 hours.</p>

                    @if(session('success'))
                        <div class="mt-6 rounded-lg border border-growth-200 bg-growth-50 p-4 text-sm text-growth-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="mt-8 space-y-6">
                        @csrf
                        <x-honeypot />
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-slate-700">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-slate-700">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium text-slate-700">Practice or Company Name</label>
                            <input type="text" id="company" name="company" value="{{ old('company') }}"
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                        </div>
                        <div>
                            <label for="service" class="block text-sm font-medium text-slate-700">Service of Interest</label>
                            <select id="service" name="service"
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                <option value="">Select a service...</option>
                                <option value="submission">Medical Claims Submission and Processing</option>
                                <option value="followups">Medical Aid Follow-Ups and Collections</option>
                                <option value="reconciliation">Payment Reconciliation and Allocation</option>
                                <option value="accounts">Patient Account Management</option>
                                <option value="reporting">Practice Financial Reporting</option>
                                <option value="onboarding">Onboarding Support for New Practices</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700">Message</label>
                            <textarea id="message" name="message" rows="4" required
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500"
                                placeholder="Tell us about your practice's billing needs...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700 sm:w-auto">
                            Send Message
                        </button>
                    </form>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-brand-900">Get in Touch</h2>
                    <p class="mt-2 text-slate-600">Prefer to reach out directly? Here is how you can contact us.</p>

                    <div class="mt-8 space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                <svg class="h-6 w-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-brand-900">Email</h3>
                                <p class="mt-1 text-slate-600">info@clearclaims.health</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-growth-50">
                                <svg class="h-6 w-6 text-growth-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-brand-900">Phone</h3>
                                <p class="mt-1 text-slate-600">071 339 5866</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                <svg class="h-6 w-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-brand-900">Location</h3>
                                <p class="mt-1 text-slate-600">South Africa</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 rounded-xl border border-slate-200 bg-slate-50 p-8">
                        <h3 class="text-lg font-semibold text-brand-900">Why Choose ClearClaims?</h3>
                        <ul class="mt-4 space-y-3">
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Faster payments and improved cash flow</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Reduced administrative workload</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Fewer rejected claims</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Professional handling of accounts</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Transparent reporting and communication</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
