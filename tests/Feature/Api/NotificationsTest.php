<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationsTest extends TestCase
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

    public function test_guest_cannot_list_notifications(): void
    {
        $this->getJson(route('api.notifications.index'))->assertUnauthorized();
    }

    public function test_user_can_list_notifications(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAsApi($user)->getJson(route('api.notifications.index'));

        $response->assertOk();
        $response->assertJsonStructure(['data']);
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $notification = $user->notifications()->create([
            'id' => Str::uuid()->toString(),
            'type' => 'App\Notifications\NewLeadsMatchSavedFilterNotification',
            'data' => ['message' => 'test'],
        ]);

        $response = $this->actingAsApi($user)->postJson(route('api.notifications.read', ['id' => $notification->id]));

        $response->assertOk();
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAsApi($user)->postJson(route('api.notifications.read-all'));

        $response->assertOk();
    }
}
