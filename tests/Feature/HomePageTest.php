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

    public function test_home_hero_uses_warm_components_and_honest_floating_chip(): void
    {
        $response = $this->get('/');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('You only pay when we collect', false);
        $response->assertSee('rounded-3xl border border-warm-border bg-warm-surface', false);
    }

    public function test_pricing_teaser_is_a_rounded_island_and_closing_cta_uses_warm_treatment(): void
    {
        $response = $this->get('/');

        $response->assertSee('rounded-[2.5rem] bg-brand-900', false);
        $response->assertSee('bg-warm-bg py-24', false);
    }
}
