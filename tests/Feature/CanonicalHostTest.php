<?php

namespace Tests\Feature;

use Tests\TestCase;

class CanonicalHostTest extends TestCase
{
    public function test_www_pages_redirect_permanently_to_apex_host(): void
    {
        $this->get('https://www.tsrepairservice.com/about?ref=1')
            ->assertStatus(301)
            ->assertRedirect('https://tsrepairservice.com/about?ref=1');
    }

    public function test_apex_pages_and_www_form_posts_are_not_redirected(): void
    {
        $this->get('https://tsrepairservice.com/sitemap.xml')->assertOk();
        $this->post('https://www.tsrepairservice.com/contact', [])->assertStatus(302)
            ->assertSessionHasErrors();
    }
}
