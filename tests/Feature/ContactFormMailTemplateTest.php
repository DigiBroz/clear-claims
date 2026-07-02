<?php

namespace Tests\Feature;

use Illuminate\Mail\Markdown;
use Tests\TestCase;

class ContactFormMailTemplateTest extends TestCase
{
    public function test_contact_form_email_includes_the_clearclaims_logo_at_nav_size(): void
    {
        $html = (string) app(Markdown::class)->render('mail.contact-form', [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'email' => 'jane@example.com',
            'company' => 'Acme Family Practice',
            'service' => 'submission',
            'body' => 'Test message body.',
        ]);

        $this->assertStringContainsString('images/logo.png', $html);
        $this->assertStringContainsString('width="216"', $html);
        $this->assertStringContainsString('height="80"', $html);
    }
}
