<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServiceUrlTest extends TestCase
{
    public function test_every_service_page_uses_the_appliance_repair_moorpark_pattern(): void
    {
        foreach (config('repair.services') as $service) {
            $this->assertMatchesRegularExpression('#^/[a-z-]+-repair-moorpark$#', $service['url'], $service['name']);
            $this->get($service['url'])->assertOk();
        }
    }

    public function test_old_service_urls_redirect_permanently(): void
    {
        foreach ([
            '/services/refrigerator-repair-moorpark' => '/refrigerator-repair-moorpark',
            '/services/dryer-repair' => '/dryer-repair-moorpark',
            '/services/electronic-repair' => '/electronic-repair-moorpark',
            '/services/washer-repair' => '/washer-repair-moorpark',
            '/services/oven-repair' => '/oven-stove-repair-moorpark',
            '/services/stove-range-repair' => '/oven-stove-repair-moorpark',
        ] as $old => $new) {
            $this->get($old)->assertStatus(301)->assertRedirect($new);
        }
    }

    public function test_static_canonical_and_og_url_match_the_service_address(): void
    {
        foreach (glob(resource_path('js/Pages/Services/*.vue')) as $file) {
            $source = file_get_contents($file);
            if (str_contains($source, ':href="canonicalUrl"')) {
                continue; // built from the current route, follows the route automatically
            }
            preg_match('#rel="canonical" href="https://tsrepairservice\.com([^"]+)"#', $source, $canonical);
            $this->assertNotEmpty($canonical, basename($file).' has no canonical link');
            $this->assertContains($canonical[1], array_column(config('repair.services'), 'url'), basename($file));
            $this->assertStringContainsString('content="https://tsrepairservice.com'.$canonical[1].'"', $source, basename($file).' og:url');
        }
    }
}
