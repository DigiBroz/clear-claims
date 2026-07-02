<?php

namespace Tests\Feature;

use Tests\TestCase;

class DevPreviewRouteTest extends TestCase
{
    public function test_preview_route_returns_404_outside_local_environment(): void
    {
        $response = $this->get('/dev/preview/contact-form');

        $response->assertNotFound();
    }

    public function test_preview_route_renders_email_in_local_environment(): void
    {
        $this->app['env'] = 'local';

        $response = $this->get('/dev/preview/contact-form');

        $response->assertOk();
        $response->assertSee('Jane', false);
    }
}
