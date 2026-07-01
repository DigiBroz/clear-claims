@extends('layouts.app')

@section('content')
    <x-warm-hero>
        <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">A Pricing Model Built Around Getting You Paid</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-600">Most medical billing services charge a flat monthly fee or a percentage of the claims they submit, whether or not those claims are ever paid. ClearClaims works differently.</p>
    </x-warm-hero>

    <section class="py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">How the Percentage of Collections Model Works</h2>
            <p class="mt-6 text-slate-600">Our fee is a percentage of the money that is successfully paid out to your practice by the medical aid. It is never charged on the value of claims submitted, and it is never charged on claims that are rejected, disputed, or never paid. If a medical aid does not pay, we do not get paid either.</p>
            <p class="mt-4 text-slate-600">This means our incentives are aligned with yours from the first claim we submit. We are not paid for volume of paperwork. We are paid for money that actually lands in your practice's account, which is why we follow up on outstanding claims as hard as we do.</p>
            <div class="mt-10">
                <x-warm-chart-card>
                    <x-arrow-motif class="h-24 w-full" />
                </x-warm-chart-card>
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Flat Fee Billing vs Percentage of Collections</h2>
            <div class="mt-10 overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="bg-brand-900 text-white">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Traditional Flat Fee Billing</th>
                            <th class="px-6 py-4 font-semibold">ClearClaims Percentage of Collections</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr>
                            <td class="px-6 py-4 text-slate-600">Charged every month regardless of collections</td>
                            <td class="px-6 py-4 text-slate-600">Charged only on money actually paid to your practice</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-slate-600">No direct incentive to chase rejected claims</td>
                            <td class="px-6 py-4 text-slate-600">Direct incentive to resolve every outstanding claim</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-slate-600">Cost is fixed even in a slow month</td>
                            <td class="px-6 py-4 text-slate-600">Cost scales naturally with your practice's income</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-slate-600">Risk sits mostly with the practice</td>
                            <td class="px-6 py-4 text-slate-600">Risk is shared with ClearClaims</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Pricing Model Questions</h2>
            <div class="mt-10 space-y-8">
                <div>
                    <h3 class="font-semibold text-brand-900">What percentage do you charge?</h3>
                    <p class="mt-2 text-slate-600">Our percentage is quoted after a short, no-obligation consultation, based on your practice's size, specialty, and claims volume. We would rather understand your practice properly than quote a generic number that does not reflect your situation.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Do you charge on claims that are submitted but not yet paid?</h3>
                    <p class="mt-2 text-slate-600">No. Our fee is calculated only on money the medical aid has actually paid to your practice, not on claims that are pending or under review.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">What happens if a claim is rejected and never resolved?</h3>
                    <p class="mt-2 text-slate-600">If a claim is never paid, we never charge a fee on it. There is no cost to your practice for claims that do not result in payment.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Is there a setup fee or minimum contract period?</h3>
                    <p class="mt-2 text-slate-600">We can discuss contract terms during your consultation. Our aim is a straightforward working relationship, not one built around lock-in fees.</p>
                </div>
            </div>
        </div>
    </section>

    <x-warm-cta>
        <h2 class="text-3xl font-bold text-brand-900">Ready to See What This Looks Like for Your Practice?</h2>
        <p class="mt-4 text-slate-700">Tell us about your practice and we will walk you through a percentage that reflects your size, specialty, and claims volume.</p>
        <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get a Quote for Your Practice</a>
    </x-warm-cta>
@endsection
