<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LeadImportRunController;
use App\Http\Controllers\Admin\LeadSourceController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\LeadBookmarkController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadExportController;
use App\Http\Controllers\LeadListController;
use App\Http\Controllers\LeadNoteController;
use App\Http\Controllers\LeadReminderController;
use App\Http\Controllers\LeadTagController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedFilterController;
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

Route::middleware(['auth', 'verified', 'active', 'onboarding.completed', 'role:user', 'permission:search-leads'])
    ->prefix('leads')
    ->name('leads.')
    ->group(function () {
        Route::get('/', [LeadController::class, 'index'])->name('index');
        Route::post('/export', [LeadExportController::class, 'store'])->name('export')->middleware('permission:export-leads');
        Route::post('/{lead}/bookmark', [LeadBookmarkController::class, 'store'])->name('bookmark.store')->middleware('permission:bookmark-leads');
        Route::delete('/{lead}/bookmark', [LeadBookmarkController::class, 'destroy'])->name('bookmark.destroy')->middleware('permission:bookmark-leads');
        Route::patch('/{lead}/status', [LeadController::class, 'updateStatus'])->name('status.update');
        Route::post('/{lead}/notes', [LeadNoteController::class, 'store'])->name('notes.store');
        Route::delete('/notes/{note}', [LeadNoteController::class, 'destroy'])->name('notes.destroy');
        Route::post('/{lead}/reminders', [LeadReminderController::class, 'store'])->name('reminders.store');
        Route::delete('/reminders/{reminder}', [LeadReminderController::class, 'destroy'])->name('reminders.destroy');
        Route::post('/{lead}/tags', [LeadTagController::class, 'store'])->name('tags.store');
        Route::delete('/{lead}/tags/{tag}', [LeadTagController::class, 'destroy'])->name('tags.destroy');
        Route::post('/{lead}/lists', [LeadListController::class, 'addLead'])->name('lists.add')->middleware('permission:manage-lists');
        Route::post('saved-filters', [SavedFilterController::class, 'store'])->name('saved-filters.store')->middleware('permission:receive-notifications');
        Route::delete('saved-filters/{savedFilter}', [SavedFilterController::class, 'destroy'])->name('saved-filters.destroy')->middleware('permission:receive-notifications');
        Route::get('/{lead}', [LeadController::class, 'show'])->name('show');
    });

Route::middleware(['auth', 'verified', 'active', 'onboarding.completed', 'role:user', 'permission:manage-lists'])
    ->prefix('lists')
    ->name('lists.')
    ->group(function () {
        Route::get('/', [LeadListController::class, 'index'])->name('index');
        Route::post('/', [LeadListController::class, 'store'])->name('store');
        Route::get('/{list}', [LeadListController::class, 'show'])->name('show');
        Route::patch('/{list}', [LeadListController::class, 'update'])->name('update');
        Route::post('/{list}/share', [LeadListController::class, 'share'])->name('share');
        Route::delete('/{list}/share/{user}', [LeadListController::class, 'unshare'])->name('unshare');
        Route::delete('/{list}', [LeadListController::class, 'destroy'])->name('destroy');
        Route::delete('/{list}/leads/{lead}', [LeadListController::class, 'removeLead'])->name('leads.remove');
    });

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
    Route::middleware('permission:manage-lead-sources')->group(function () {
        Route::post('lead-sources/{leadSource}/pause', [LeadSourceController::class, 'pause'])->name('lead-sources.pause');
        Route::post('lead-sources/{leadSource}/sync', [LeadSourceController::class, 'sync'])->name('lead-sources.sync');
        Route::resource('lead-sources', LeadSourceController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        Route::resource('import-runs', LeadImportRunController::class)->only(['index', 'show']);
    });
    Route::get('plans', [PlanController::class, 'index'])->name('plans.index')->middleware('permission:manage-subscription-plans');
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index')->middleware('permission:manage-payments');
});

require __DIR__.'/auth.php';
