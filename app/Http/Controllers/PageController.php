<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;

class PageController extends Controller
{
    private function setSeoImage(): void
    {
        $image = asset('images/og-image.png').'?v='.filemtime(public_path('images/og-image.png'));

        SEOTools::opengraph()->addImage($image);
        SEOTools::twitter()->setImage($image);
        SEOTools::jsonLd()->addImage($image);
    }

    private function setMedicalBusinessJsonLd(): void
    {
        SEOTools::jsonLd()->setType('MedicalBusiness');
        SEOTools::jsonLd()->addValue('telephone', '+27713395866');
        SEOTools::jsonLd()->addValue('email', 'info@clearclaims.health');
        SEOTools::jsonLd()->addValue('address', [
            '@type' => 'PostalAddress',
            'addressCountry' => 'ZA',
        ]);
    }

    public function home()
    {
        SEOTools::setTitle('Medical Billing and Practice Support for South African Healthcare Providers');
        SEOTools::setDescription('ClearClaims Health Accounts handles medical claims submission, medical aid follow-ups, payment reconciliation, and practice reporting so your practice gets paid faster with less administrative burden.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.home');
    }

    public function services()
    {
        SEOTools::setTitle('Medical Billing Services');
        SEOTools::setDescription('Explore ClearClaims full medical billing service, from claims submission and medical aid follow-ups to payment reconciliation, patient account management, financial reporting, and onboarding support.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.services');
    }
}
