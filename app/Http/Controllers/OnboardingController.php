<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardingProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding flow (current step).
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasCompletedOnboarding()) {
            return $this->redirectAfterOnboarding($user);
        }

        $step = (int) session('onboarding_step', 1);

        if ($step === 1) {
            return view('onboarding.welcome');
        }

        return view('onboarding.profile', ['user' => $user]);
    }

    /**
     * Redirect user to the appropriate dashboard after onboarding.
     */
    private function redirectAfterOnboarding($user): RedirectResponse
    {
        return redirect()->route($user->hasRole('admin') ? 'admin.dashboard' : 'dashboard');
    }

    /**
     * Handle onboarding form submission.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasCompletedOnboarding()) {
            return $this->redirectAfterOnboarding($user);
        }

        $step = (int) session('onboarding_step', 1);

        if ($step === 1) {
            session(['onboarding_step' => 2]);

            return redirect()->route('onboarding');
        }

        $user->update(['onboarding_completed_at' => now()]);
        session()->forget('onboarding_step');

        return $this->redirectAfterOnboarding($user);
    }

    /**
     * Save optional profile step and complete onboarding.
     */
    public function profile(OnboardingProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasCompletedOnboarding()) {
            return $this->redirectAfterOnboarding($user);
        }

        $user->update($request->validated());
        $user->update(['onboarding_completed_at' => now()]);
        session()->forget('onboarding_step');

        return $this->redirectAfterOnboarding($user);
    }

    /**
     * Skip optional profile step and complete onboarding.
     */
    public function skip(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasCompletedOnboarding()) {
            return $this->redirectAfterOnboarding($user);
        }

        $user->update(['onboarding_completed_at' => now()]);
        session()->forget('onboarding_step');

        return $this->redirectAfterOnboarding($user);
    }
}
