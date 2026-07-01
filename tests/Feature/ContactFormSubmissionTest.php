<?php

namespace Tests\Feature;

use App\Events\ContactFormSubmitted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactFormSubmissionTest extends TestCase
{
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'company' => 'Smith Family Practice',
            'service' => 'submission',
            'message' => 'We would like to discuss moving our billing to ClearClaims.',
        ], $overrides);
    }

    public function test_valid_submission_dispatches_event_and_redirects_with_success(): void
    {
        Event::fake([ContactFormSubmitted::class]);

        $response = $this->post('/contact', $this->validPayload());

        $response->assertRedirect();
        $response->assertSessionHas('success');
        Event::assertDispatched(ContactFormSubmitted::class, function (ContactFormSubmitted $event) {
            return $event->firstName === 'Jane'
                && $event->lastName === 'Smith'
                && $event->email === 'jane@example.com';
        });
    }

    public function test_missing_required_fields_fail_validation(): void
    {
        $response = $this->post('/contact', $this->validPayload(['first_name' => '']));

        $response->assertSessionHasErrors('first_name');
    }

    public function test_submission_notifies_clearclaims_inbox(): void
    {
        Notification::fake();

        $this->post('/contact', $this->validPayload());

        Notification::assertSentOnDemand(
            \App\Notifications\ContactFormNotification::class,
            function ($notification, $channels, $notifiable) {
                return in_array('info@clearclaims.health', $notifiable->routes['mail']);
            }
        );
    }

    public function test_honeypot_field_blocks_bot_submissions(): void
    {
        Event::fake([ContactFormSubmitted::class]);

        $payload = $this->validPayload();
        $payload['my_name'] = 'a spam bot filled this in';

        $this->post('/contact', $payload);

        Event::assertNotDispatched(ContactFormSubmitted::class);
    }

    public function test_contact_route_is_rate_limited(): void
    {
        Event::fake([ContactFormSubmitted::class]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $this->validPayload());
        }

        $response = $this->post('/contact', $this->validPayload());

        $response->assertStatus(429);
    }
}
