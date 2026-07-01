<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_loads_with_expected_content(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ClearClaims', false);
        $response->assertSee('percentage of collections', false);
        $response->assertSee('Book a Free Consultation', false);
        $response->assertSee('Medical Claims Submission', false);
    }

    public function test_home_page_sets_seo_title_and_description(): void
    {
        $response = $this->get('/');

        $response->assertSee('Get Paid Faster', false);
        $response->assertSee('medical billing', false);
    }
}
