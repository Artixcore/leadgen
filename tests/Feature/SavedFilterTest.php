<?php

namespace Tests\Feature;

use App\Models\SavedFilter;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    protected function userWithNotifications(): User
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $user->givePermissionTo('search-leads');
        $user->givePermissionTo('receive-notifications');

        return $user;
    }

    public function test_user_can_save_current_search_as_filter(): void
    {
        $user = $this->userWithNotifications();

        $response = $this->actingAs($user)->post(route('leads.saved-filters.store'), [
            'name' => 'Tech in USA',
            'criteria' => [
                'industry' => 'Technology',
                'country' => 'United States',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('saved_filters', [
            'user_id' => $user->id,
            'name' => 'Tech in USA',
        ]);
        $filter = SavedFilter::where('user_id', $user->id)->first();
        $this->assertSame('Technology', $filter->criteria['industry'] ?? null);
        $this->assertSame('United States', $filter->criteria['country'] ?? null);
    }

    public function test_user_can_delete_own_saved_filter(): void
    {
        $user = $this->userWithNotifications();
        $filter = SavedFilter::create([
            'user_id' => $user->id,
            'name' => 'My Filter',
            'criteria' => ['country' => 'UK'],
        ]);

        $response = $this->actingAs($user)->delete(route('leads.saved-filters.destroy', $filter));

        $response->assertRedirect();
        $this->assertDatabaseMissing('saved_filters', ['id' => $filter->id]);
    }

    public function test_user_cannot_delete_another_users_saved_filter(): void
    {
        $user = $this->userWithNotifications();
        $other = User::factory()->completedOnboarding()->create();
        $other->assignRole('user');
        $filter = SavedFilter::create([
            'user_id' => $other->id,
            'name' => 'Other Filter',
            'criteria' => [],
        ]);

        $response = $this->actingAs($user)->delete(route('leads.saved-filters.destroy', $filter));

        $response->assertForbidden();
        $this->assertDatabaseHas('saved_filters', ['id' => $filter->id]);
    }
}
