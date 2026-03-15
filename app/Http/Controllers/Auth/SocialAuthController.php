<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialAuthController extends Controller
{
    private const ALLOWED_PROVIDERS = ['google'];

    /**
     * Redirect to the OAuth provider.
     */
    public function redirect(string $provider): RedirectResponse|Response
    {
        if (! in_array($provider, self::ALLOWED_PROVIDERS, true)) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the OAuth callback.
     */
    public function callback(string $provider): RedirectResponse
    {
        if (! in_array($provider, self::ALLOWED_PROVIDERS, true)) {
            abort(404);
        }

        $oauthUser = Socialite::driver($provider)->user();

        if (Auth::check()) {
            return $this->linkAccount(Auth::user(), $provider, $oauthUser);
        }

        return $this->loginOrRegister($provider, $oauthUser);
    }

    private function linkAccount(User $user, string $provider, SocialiteUser $oauthUser): RedirectResponse
    {
        $user->update([
            'oauth_provider' => $provider,
            'oauth_id' => $oauthUser->getId(),
            'avatar' => $oauthUser->getAvatar(),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with('status', __('Google account linked.'));
    }

    private function loginOrRegister(string $provider, SocialiteUser $oauthUser): RedirectResponse
    {
        $user = User::query()
            ->where('oauth_provider', $provider)
            ->where('oauth_id', $oauthUser->getId())
            ->first();

        if ($user) {
            Auth::login($user);

            return $this->redirectAfterAuth($user);
        }

        $user = User::query()->where('email', $oauthUser->getEmail())->first();

        if ($user) {
            $user->update([
                'oauth_provider' => $provider,
                'oauth_id' => $oauthUser->getId(),
                'avatar' => $oauthUser->getAvatar(),
            ]);
            Auth::login($user);

            return $this->redirectAfterAuth($user);
        }

        $user = User::create([
            'name' => $oauthUser->getName() ?? $oauthUser->getEmail(),
            'email' => $oauthUser->getEmail(),
            'password' => Hash::make(Str::random(32)),
            'oauth_provider' => $provider,
            'oauth_id' => $oauthUser->getId(),
            'avatar' => $oauthUser->getAvatar(),
            'email_verified_at' => $oauthUser->getEmail() ? now() : null,
        ]);
        $user->assignRole('user');
        Auth::login($user);

        return redirect()->route('onboarding');
    }

    private function redirectAfterAuth(User $user): RedirectResponse
    {
        if (! $user->hasCompletedOnboarding()) {
            return redirect()->route('onboarding');
        }

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
