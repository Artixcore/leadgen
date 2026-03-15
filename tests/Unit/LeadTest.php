<?php

namespace Tests\Unit;

use App\LeadFreshness;
use App\LeadSourceStatus;
use App\Models\Lead;
use App\Models\LeadSource;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_from_trusted_sources_scope_only_returns_leads_from_active_trusted_sources(): void
    {
        $trustedSource = LeadSource::factory()->create(['status' => LeadSourceStatus::Active, 'is_trusted' => true]);
        $inactiveSource = LeadSource::factory()->create(['status' => LeadSourceStatus::Inactive, 'is_trusted' => true]);
        $untrustedSource = LeadSource::factory()->create(['status' => LeadSourceStatus::Active, 'is_trusted' => false]);

        $trustedLead = Lead::factory()->create(['lead_source_id' => $trustedSource->id]);
        Lead::factory()->create(['lead_source_id' => $inactiveSource->id]);
        Lead::factory()->create(['lead_source_id' => $untrustedSource->id]);

        $visible = Lead::fromTrustedSources()->get();

        $this->assertCount(1, $visible);
        $this->assertTrue($visible->first()->is($trustedLead));
    }

    public function test_duplicate_lead_prevention_unique_constraint_on_email_and_lead_source_id(): void
    {
        $source = LeadSource::factory()->create();
        Lead::factory()->create(['email' => 'dup@example.com', 'lead_source_id' => $source->id]);

        $this->expectException(QueryException::class);
        Lead::factory()->create(['email' => 'dup@example.com', 'lead_source_id' => $source->id]);
    }

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
