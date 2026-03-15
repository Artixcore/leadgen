<?php

namespace Tests\Feature\Api;

use App\Models\SavedFilter;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FiltersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    protected function actingAsApi(User $user): static
    {
        $token = $user->createToken('api')->plainTextToken;

        return $this->withHeader('Authorization', 'Bearer '.$token);
    }

    public function test_guest_cannot_list_filters(): void
    {
        $this->getJson(route('api.filters.index'))->assertUnauthorized();
    }

    public function test_user_can_list_own_saved_filters(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        SavedFilter::create(['user_id' => $user->id, 'name' => 'F1', 'criteria' => []]);
        SavedFilter::create(['user_id' => $user->id, 'name' => 'F2', 'criteria' => []]);

        $response = $this->actingAsApi($user)->getJson(route('api.filters.index'));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_user_can_create_saved_filter(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAsApi($user)->postJson(route('api.filters.store'), [
            'name' => 'My Filter',
            'criteria' => ['country' => 'US', 'industry' => 'Tech'],
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.name', 'My Filter');
        $this->assertDatabaseHas('saved_filters', ['user_id' => $user->id, 'name' => 'My Filter']);
    }

    public function test_user_can_show_own_saved_filter(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $filter = SavedFilter::create(['user_id' => $user->id, 'name' => 'Test Filter', 'criteria' => []]);

        $response = $this->actingAsApi($user)->getJson(route('api.filters.show', $filter));

        $response->assertOk();
        $response->assertJsonPath('data.name', 'Test Filter');
    }

    public function test_user_cannot_show_other_users_saved_filter(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $other = User::factory()->create();
        $filter = SavedFilter::create(['user_id' => $other->id, 'name' => 'Other', 'criteria' => []]);

        $response = $this->actingAsApi($user)->getJson(route('api.filters.show', $filter));

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_saved_filter(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $filter = SavedFilter::create(['user_id' => $user->id, 'name' => 'To Delete', 'criteria' => []]);

        $response = $this->actingAsApi($user)->deleteJson(route('api.filters.destroy', $filter));

        $response->assertOk();
        $this->assertDatabaseMissing('saved_filters', ['id' => $filter->id]);
    }
}
