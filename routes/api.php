<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\BillingController as ApiBillingController;
use App\Http\Controllers\Api\ExportController as ApiExportController;
use App\Http\Controllers\Api\LeadController as ApiLeadController;
use App\Http\Controllers\Api\LeadListController as ApiLeadListController;
use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use App\Http\Controllers\Api\SavedFilterController as ApiSavedFilterController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {
    Route::middleware('throttle:api-auth')->group(function () {
        Route::post('/login', [ApiAuthController::class, 'login'])->name('login');
        Route::post('/register', [ApiAuthController::class, 'register'])->name('register');
    });

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
        Route::get('/user', [ApiUserController::class, 'show'])->name('user');

        Route::middleware(['permission:search-leads', 'api.plan'])->group(function () {
            Route::get('/leads', [ApiLeadController::class, 'index'])->name('leads.index');
            Route::get('/leads/{lead}', [ApiLeadController::class, 'show'])->name('leads.show');
        });

        Route::middleware(['permission:search-leads'])->group(function () {
            Route::get('/filters', [ApiSavedFilterController::class, 'index'])->name('filters.index');
            Route::post('/filters', [ApiSavedFilterController::class, 'store'])->name('filters.store')->middleware('permission:receive-notifications');
            Route::get('/filters/{savedFilter}', [ApiSavedFilterController::class, 'show'])->name('filters.show');
            Route::delete('/filters/{savedFilter}', [ApiSavedFilterController::class, 'destroy'])->name('filters.destroy');
        });

        Route::middleware(['permission:manage-lists'])->group(function () {
            Route::get('/lists', [ApiLeadListController::class, 'index'])->name('lists.index');
            Route::post('/lists', [ApiLeadListController::class, 'store'])->name('lists.store');
            Route::get('/lists/{list}', [ApiLeadListController::class, 'show'])->name('lists.show');
            Route::patch('/lists/{list}', [ApiLeadListController::class, 'update'])->name('lists.update');
            Route::delete('/lists/{list}', [ApiLeadListController::class, 'destroy'])->name('lists.destroy');
            Route::post('/lists/{list}/leads', [ApiLeadListController::class, 'addLeads'])->name('lists.leads.store');
            Route::delete('/lists/{list}/leads/{lead}', [ApiLeadListController::class, 'removeLead'])->name('lists.leads.destroy');
        });

        Route::middleware(['permission:export-leads', 'api.plan'])->group(function () {
            Route::post('/exports', [ApiExportController::class, 'store'])->name('exports.store');
            Route::get('/exports/{export}', [ApiExportController::class, 'show'])->name('exports.show');
            Route::get('/exports/{export}/download', [ApiExportController::class, 'download'])->name('exports.download');
        });

        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [ApiBillingController::class, 'index'])->name('index');
            Route::get('/plans', [ApiBillingController::class, 'plans'])->name('plans');
            Route::get('/invoices', [ApiBillingController::class, 'invoices'])->name('invoices');
            Route::get('/invoices/{id}/download', [ApiBillingController::class, 'downloadInvoice'])->name('invoices.download');
        });

        Route::get('/notifications', [ApiNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [ApiNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::post('/notifications/{id}/read', [ApiNotificationController::class, 'markAsRead'])->name('notifications.read');
    });
});
