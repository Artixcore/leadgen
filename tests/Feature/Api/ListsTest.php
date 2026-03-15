<?php

namespace Tests\Feature\Api;

use App\Models\Lead;
use App\Models\LeadList;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(PlanSeeder::class);
    }

    protected function actingAsApi(User $user): static
    {
        $token = $user->createToken('api')->plainTextToken;

        return $this->withHeader('Authorization', 'Bearer '.$token);
    }

    public function test_guest_cannot_list_lists(): void
    {
        $this->getJson(route('api.lists.index'))->assertUnauthorized();
    }

    public function test_user_can_list_own_lists(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        LeadList::create(['user_id' => $user->id, 'name' => 'My List']);

        $response = $this->actingAsApi($user)->getJson(route('api.lists.index'));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    public function test_user_can_create_list(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAsApi($user)->postJson(route('api.lists.store'), [
            'name' => 'New List',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.name', 'New List');
        $this->assertDatabaseHas('lead_lists', ['user_id' => $user->id, 'name' => 'New List']);
    }

    public function test_user_can_update_own_list(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $list = LeadList::create(['user_id' => $user->id, 'name' => 'Old Name']);

        $response = $this->actingAsApi($user)->patchJson(route('api.lists.update', $list), [
            'name' => 'Updated Name',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.name', 'Updated Name');
    }

    public function test_user_can_delete_own_list(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $list = LeadList::create(['user_id' => $user->id, 'name' => 'To Delete']);

        $response = $this->actingAsApi($user)->deleteJson(route('api.lists.destroy', $list));

        $response->assertOk();
        $this->assertSoftDeleted('lead_lists', ['id' => $list->id]);
    }

    public function test_user_can_add_leads_to_list(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $list = LeadList::create(['user_id' => $user->id, 'name' => 'List']);
        $lead = Lead::factory()->create();

        $response = $this->actingAsApi($user)->postJson(route('api.lists.leads.store', $list), [
            'lead_ids' => [$lead->id],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('lead_list_items', ['lead_list_id' => $list->id, 'lead_id' => $lead->id]);
    }

    public function test_user_can_remove_lead_from_list(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $list = LeadList::create(['user_id' => $user->id, 'name' => 'List']);
        $lead = Lead::factory()->create();
        $list->leads()->attach($lead->id);

        $response = $this->actingAsApi($user)->deleteJson(route('api.lists.leads.destroy', [$list, $lead]));

        $response->assertOk();
        $this->assertDatabaseMissing('lead_list_items', ['lead_list_id' => $list->id, 'lead_id' => $lead->id]);
    }
}
