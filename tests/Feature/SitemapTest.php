<?php

namespace Tests\Feature;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_sitemap_lists_unique_current_pages_without_legacy_or_query_urls(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $urls = array_map('strval', $xml->xpath('//*[local-name()="loc"]'));
        $this->assertCount(13, $urls);
        $this->assertSame($urls, array_values(array_unique($urls)));
        foreach (config('repair.services') as $service) {
            $this->assertContains('https://tsrepairservice.com'.$service['url'], $urls);
        }
        $this->assertNotContains('https://tsrepairservice.com/extra', $urls);
        $this->assertNotContains('https://tsrepairservice.com/services/washer-repair', $urls);
        foreach ($urls as $url) {
            $this->assertNull(parse_url($url, PHP_URL_QUERY));
        }
    }
}
