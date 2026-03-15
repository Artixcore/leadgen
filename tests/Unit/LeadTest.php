<?php

namespace Tests\Unit;

use App\LeadFreshness;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_freshness_returns_fresh_when_updated_within_30_days(): void
    {
        $lead = Lead::factory()->create(['updated_at' => now()->subDays(10)]);

        $this->assertSame(LeadFreshness::Fresh, $lead->freshness());
    }

    public function test_freshness_returns_stale_when_updated_between_30_and_90_days(): void
    {
        $lead = Lead::factory()->create(['updated_at' => now()->subDays(50)]);

        $this->assertSame(LeadFreshness::Stale, $lead->freshness());
    }

    public function test_freshness_returns_unknown_when_updated_over_90_days_ago(): void
    {
        $lead = Lead::factory()->create(['updated_at' => now()->subDays(100)]);

        $this->assertSame(LeadFreshness::Unknown, $lead->freshness());
    }
}
