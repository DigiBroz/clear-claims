<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoFilesTest extends TestCase
{
    public function test_sitemap_lists_all_pages(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee('https://clearclaims.health/', false);
        $response->assertSee('https://clearclaims.health/services', false);
        $response->assertSee('https://clearclaims.health/pricing', false);
        $response->assertSee('https://clearclaims.health/about', false);
        $response->assertSee('https://clearclaims.health/contact', false);
    }

    public function test_robots_txt_allows_all_crawlers(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertSee('User-agent: *', false);
        $response->assertSee('Sitemap: https://clearclaims.health/sitemap.xml', false);
    }
}
