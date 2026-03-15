<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingTest extends TestCase
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

    public function test_guest_cannot_access_billing(): void
    {
        $this->getJson(route('api.billing.index'))->assertUnauthorized();
        $this->getJson(route('api.billing.plans'))->assertUnauthorized();
    }

    public function test_user_can_get_billing_index(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAsApi($user)->getJson(route('api.billing.index'));

        $response->assertOk();
        $response->assertJsonStructure(['subscription', 'plan' => ['id', 'name', 'slug'], 'usage' => ['period', 'leads_count', 'exports_count']]);
    }

    public function test_user_can_list_plans(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAsApi($user)->getJson(route('api.billing.plans'));

        $response->assertOk();
        $response->assertJsonStructure(['plans', 'current_plan']);
    }
}
