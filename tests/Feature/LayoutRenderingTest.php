<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutRenderingTest extends TestCase
{
    public function test_dashboard_route_returns_ok(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_dashboard_contains_pjl_connect_branding(): void
    {
        $response = $this->get('/dashboard');

        $response->assertSee('PJL Connect');
    }

    public function test_dashboard_contains_welcome_message(): void
    {
        $response = $this->get('/dashboard');

        $response->assertSee('Welcome to PJL Connect');
    }

    public function test_dashboard_contains_sidebar_navigation(): void
    {
        $response = $this->get('/dashboard');

        $response->assertSee('Dashboard');
        $response->assertSee('Jobs');
        $response->assertSee('Bookings');
        $response->assertSee('Tracking');
        $response->assertSee('Customers');
        $response->assertSee('Compliance');
        $response->assertSee('Financial');
        $response->assertSee('Admin');
    }

    public function test_dashboard_contains_navigation_sections(): void
    {
        $response = $this->get('/dashboard');

        $response->assertSee('Operations');
        $response->assertSee('Compliance');
        $response->assertSee('Finance');
        $response->assertSee('Administration');
    }

    public function test_dashboard_contains_stat_cards(): void
    {
        $response = $this->get('/dashboard');

        $response->assertSee('Active Jobs');
        $response->assertSee('Pending Bookings');
        $response->assertSee('Active Drivers');
        $response->assertSee('Exceptions');
    }

    public function test_dashboard_contains_footer(): void
    {
        $response = $this->get('/dashboard');

        $response->assertSee('PJL Connect. All rights reserved.');
        // Laravel version only shows in 'local' environment (L2 security fix)
        // In 'testing' env, it should NOT appear
        $response->assertDontSee('Powered by Laravel', false);
    }

    public function test_dashboard_has_named_route(): void
    {
        $this->assertEquals(url('/dashboard'), route('dashboard'));
    }

    public function test_dashboard_uses_app_layout(): void
    {
        $response = $this->get('/dashboard');

        // Verify the layout includes key structural elements
        $response->assertSee('sidebar', false);
        $response->assertSee('Toggle sidebar', false);
    }

    public function test_livewire_scripts_are_loaded(): void
    {
        $response = $this->get('/dashboard');

        // Livewire 4 auto-injects its scripts; verify presence
        $response->assertSee('livewire', false);
    }
}
