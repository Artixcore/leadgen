<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
    ->name('auth.social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->name('auth.social.callback');

Route::get('/account/suspended', fn () => view('auth.account-suspended'))
    ->middleware('auth')
    ->name('account.suspended');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::post('/onboarding/profile', [OnboardingController::class, 'profile'])->name('onboarding.profile');
    Route::post('/onboarding/skip', [OnboardingController::class, 'skip'])->name('onboarding.skip');
});

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified', 'active', 'onboarding.completed', 'role:user'])
    ->name('dashboard');

Route::middleware(['auth', 'active', 'onboarding.completed'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/social/unlink', [ProfileController::class, 'unlinkSocial'])->name('profile.social.unlink');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::get('/plans', [BillingController::class, 'plans'])->name('plans');
        Route::get('/checkout/{plan}', [BillingController::class, 'checkout'])->name('checkout')->middleware('can:view,plan');
        Route::get('/portal', [BillingController::class, 'portal'])->name('portal');
        Route::get('/invoices', [BillingController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{id}/download', [BillingController::class, 'downloadInvoice'])->name('invoices.download');
    });
});

Route::middleware(['auth', 'active', 'onboarding.completed', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update']);
    Route::get('plans', [PlanController::class, 'index'])->name('plans.index')->middleware('permission:manage-subscription-plans');
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index')->middleware('permission:manage-payments');
});

require __DIR__.'/auth.php';
