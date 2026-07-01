<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServicesPageTest extends TestCase
{
    public function test_services_page_lists_all_six_services(): void
    {
        $response = $this->get('/services');

        $response->assertOk();
        $response->assertSee('Medical Claims Submission and Processing', false);
        $response->assertSee('Medical Aid Follow-Ups and Collections', false);
        $response->assertSee('Payment Reconciliation and Allocation', false);
        $response->assertSee('Patient Account Management', false);
        $response->assertSee('Practice Financial Reporting', false);
        $response->assertSee('Onboarding Support for New Practices', false);
    }
}
