<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class SocialAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_redirect_to_google_returns_redirect_response(): void
    {
        $response = $this->get(route('auth.social.redirect', ['provider' => 'google']));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_invalid_provider_returns_404(): void
    {
        $response = $this->get(route('auth.social.redirect', ['provider' => 'invalid']));

        $response->assertStatus(404);
    }

    public function test_callback_creates_new_user_and_redirects_to_onboarding(): void
    {
        $socialiteUser = new SocialiteUser;
        $socialiteUser->id = 'google-123';
        $socialiteUser->name = 'John Doe';
        $socialiteUser->email = 'john@example.com';
        $socialiteUser->avatar = 'https://example.com/avatar.png';

        $driver = Mockery::mock(Provider::class);
        $driver->shouldReceive('user')->andReturn($socialiteUser);
        Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $response = $this->get(route('auth.social.callback', ['provider' => 'google']));

        $response->assertRedirect(route('onboarding'));
        $this->assertAuthenticated();
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('google', $user->oauth_provider);
        $this->assertSame('google-123', $user->oauth_id);
        $this->assertNull($user->onboarding_completed_at);
    }
}
