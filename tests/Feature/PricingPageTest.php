<?php

namespace Tests\Feature;

use Tests\TestCase;

class PricingPageTest extends TestCase
{
    public function test_pricing_page_explains_the_collections_model(): void
    {
        $response = $this->get('/pricing');

        $response->assertOk();
        $response->assertSee('A Pricing Model Built Around Getting You Paid', false);
        $response->assertSee('percentage of the money that is successfully paid out', false);
        $response->assertSee('never charged on the value of claims submitted', false);
        $response->assertSee('What percentage do you charge', false);
    }

    public function test_pricing_hero_and_model_chart_use_warm_treatment(): void
    {
        $response = $this->get('/pricing');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('rounded-3xl border border-warm-border bg-warm-surface', false);
        $response->assertSee('rounded-full', false);
    }
}
