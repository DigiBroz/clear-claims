<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutRedesignTest extends TestCase
{
    public function test_nav_and_footer_use_the_warm_theme_and_larger_logo(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('bg-warm-surface/90', false);
        $response->assertSee('border-warm-border', false);
        $response->assertSee('h-20 w-auto', false);
        $response->assertSee('bg-warm-bg', false);
    }

    public function test_nav_cta_is_a_pill_button(): void
    {
        $response = $this->get('/');

        $response->assertSee('rounded-full', false);
    }
}
