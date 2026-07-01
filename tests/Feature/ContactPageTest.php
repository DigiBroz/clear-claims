<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function test_contact_page_shows_form_and_details(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('071 339 5866', false);
        $response->assertSee('info@clearclaims.health', false);
        $response->assertSee('Fewer rejected claims', false);
    }

    public function test_contact_hero_uses_warm_treatment_and_submit_button_is_a_pill(): void
    {
        $response = $this->get('/contact');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('rounded-full', false);
    }
}
