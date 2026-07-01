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

    public function pricing()
    {
        SEOTools::setTitle('Our Pricing Model');
        SEOTools::setDescription('ClearClaims charges a percentage of collections, calculated only on money medical aids actually pay to your practice, not on submitted claims. See how our pricing model compares to flat fee billing.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.pricing');
    }

    public function about()
    {
        SEOTools::setTitle('About ClearClaims Health Accounts');
        SEOTools::setDescription('ClearClaims Health Accounts is a South African medical billing and practice support company built around accuracy, efficiency, confidentiality, and professional service.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.about');
    }

    public function contact()
    {
        SEOTools::setTitle('Contact Us');
        SEOTools::setDescription('Get in touch with ClearClaims Health Accounts. Tell us about your practice and we will show you how our medical billing and practice support services can help.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.contact');
    }
}
