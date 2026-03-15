<?php

namespace Tests\Feature;

use App\Models\LeadSearchQuery;
use App\Models\SavedLeadSearch;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(PlanSeeder::class);
    }

    protected function userWithLeadSearchPermission(): User
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        return $user;
    }

    public function test_guest_cannot_access_lead_search_index(): void
    {
        $response = $this->get(route('lead-search.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_with_use_lead_search_permission_can_access_lead_search_index(): void
    {
        $user = $this->userWithLeadSearchPermission();

        $response = $this->actingAs($user)->get(route('lead-search.index'));

        $response->assertOk();
        $response->assertSee(__('Lead Search'));
    }

    public function test_user_can_run_lead_search_and_see_results(): void
    {
        $user = $this->userWithLeadSearchPermission();

        $response = $this->actingAs($user)->post(route('lead-search.run'), [
            'query' => 'digital marketing leads in Dubai',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertStringContainsString('results', $response->headers->get('Location'));

        $query = LeadSearchQuery::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($query);
        $this->assertSame('digital marketing leads in Dubai', $query->query);
        $this->assertSame('completed', $query->status);
    }

    public function test_user_can_view_search_results_page(): void
    {
        $user = $this->userWithLeadSearchPermission();
        $searchQuery = LeadSearchQuery::factory()->create(['user_id' => $user->id, 'status' => 'completed', 'total_results' => 2]);

        $response = $this->actingAs($user)->get(route('lead-search.results', $searchQuery));

        $response->assertOk();
        $response->assertViewHas('searchQuery', $searchQuery);
    }

    public function test_user_cannot_view_another_users_search_results(): void
    {
        $user = $this->userWithLeadSearchPermission();
        $otherUser = User::factory()->completedOnboarding()->create();
        $otherUser->assignRole('user');
        $searchQuery = LeadSearchQuery::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('lead-search.results', $searchQuery));

        $response->assertForbidden();
    }

    public function test_user_can_view_search_history(): void
    {
        $user = $this->userWithLeadSearchPermission();
        LeadSearchQuery::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('lead-search.history'));

        $response->assertOk();
        $response->assertViewHas('queries');
        $this->assertSame(2, $response->viewData('queries')->total());
    }

    public function test_user_can_view_saved_searches_page(): void
    {
        $user = $this->userWithLeadSearchPermission();

        $response = $this->actingAs($user)->get(route('lead-search.saved'));

        $response->assertOk();
        $response->assertSee(__('Saved searches'));
    }

    public function test_user_can_save_a_search(): void
    {
        $user = $this->userWithLeadSearchPermission();

        $response = $this->actingAs($user)->post(route('lead-search.saved.store'), [
            'name' => 'My Dubai search',
            'query' => 'digital marketing leads in Dubai',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('lead-search.saved'));
        $this->assertDatabaseHas('saved_lead_searches', [
            'user_id' => $user->id,
            'name' => 'My Dubai search',
            'query' => 'digital marketing leads in Dubai',
        ]);
    }

    public function test_user_can_delete_saved_search(): void
    {
        $user = $this->userWithLeadSearchPermission();
        $saved = SavedLeadSearch::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('lead-search.saved.destroy', $saved), [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect(route('lead-search.saved'));
        $this->assertDatabaseMissing('saved_lead_searches', ['id' => $saved->id]);
    }
}
