<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_states_mission_and_who_is_served(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('accurate, efficient, and transparent medical billing solutions', false);
        $response->assertSee('general practitioners, specialists, and allied health practices', false);
    }
}
