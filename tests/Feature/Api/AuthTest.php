<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_api_login_returns_token_and_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
        $response->assertJsonPath('user.id', $user->id);
    }

    public function test_api_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function test_api_login_fails_when_account_suspended(): void
    {
        $user = User::factory()->suspended()->create();
        $user->assignRole('user');

        $response = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    public function test_api_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson(route('api.register'), [
            'name' => 'Test User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_api_register_validation_fails_with_invalid_data(): void
    {
        $response = $this->postJson(route('api.register'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertUnprocessable();
    }

    public function test_api_logout_revokes_token(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('api.logout'));

        $response->assertOk();
        $this->assertCount(0, $user->fresh()->tokens);
    }
}
