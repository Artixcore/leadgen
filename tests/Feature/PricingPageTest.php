<?php

namespace Tests\Feature;

use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PlanSeeder::class);
    }

    public function test_guest_can_view_pricing_page(): void
    {
        $response = $this->get(route('pricing'));

        $response->assertOk();
        $response->assertViewIs('pricing');
        $response->assertViewHas('plans');
        $response->assertSee(__('Pricing'));
    }

    public function test_pricing_page_shows_plans(): void
    {
        $response = $this->get(route('pricing'));

        $response->assertOk();
        $plans = $response->viewData('plans');
        $this->assertGreaterThan(0, $plans->count());
    }
}
