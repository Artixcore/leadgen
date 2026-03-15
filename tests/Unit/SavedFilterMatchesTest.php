<?php

namespace Tests\Unit;

use App\Models\Lead;
use App\Models\SavedFilter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedFilterMatchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_matches_returns_true_when_lead_meets_all_criteria(): void
    {
        $user = User::factory()->create();
        $filter = SavedFilter::create([
            'user_id' => $user->id,
            'name' => 'Tech US',
            'criteria' => ['industry' => 'Technology', 'country' => 'United States'],
        ]);
        $lead = Lead::factory()->create(['industry' => 'Technology', 'country' => 'United States']);

        $this->assertTrue($filter->matches($lead));
    }

    public function test_matches_returns_false_when_industry_does_not_match(): void
    {
        $user = User::factory()->create();
        $filter = SavedFilter::create([
            'user_id' => $user->id,
            'name' => 'Tech',
            'criteria' => ['industry' => 'Technology'],
        ]);
        $lead = Lead::factory()->create(['industry' => 'Healthcare']);

        $this->assertFalse($filter->matches($lead));
    }

    public function test_matches_returns_false_when_exclude_duplicates_and_lead_is_duplicate(): void
    {
        $user = User::factory()->create();
        $filter = SavedFilter::create([
            'user_id' => $user->id,
            'name' => 'No dupes',
            'criteria' => ['exclude_duplicates' => true],
        ]);
        $first = Lead::factory()->create(['email' => 'dup@example.com']);
        $duplicate = Lead::factory()->create(['email' => 'dup@example.com']);
        $duplicate->refresh();

        $this->assertTrue($duplicate->is_duplicate);
        $this->assertFalse($filter->matches($duplicate));
    }

    public function test_matches_returns_true_when_search_q_in_company_name(): void
    {
        $user = User::factory()->create();
        $filter = SavedFilter::create([
            'user_id' => $user->id,
            'name' => 'Acme',
            'criteria' => ['q' => 'Acme'],
        ]);
        $lead = Lead::factory()->create(['company_name' => 'Acme Corp']);

        $this->assertTrue($filter->matches($lead));
    }
}
