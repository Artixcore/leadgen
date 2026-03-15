<?php

use App\Http\Controllers\Api\ExportController as ApiExportController;
use App\Http\Controllers\Api\LeadController as ApiLeadController;
use App\Http\Controllers\Api\LeadListController as ApiLeadListController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->name('api.')->group(function () {
    Route::get('/user', [ApiUserController::class, 'show'])->name('user');

    Route::middleware(['permission:search-leads', 'api.plan'])->group(function () {
        Route::get('/leads', [ApiLeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/{lead}', [ApiLeadController::class, 'show'])->name('leads.show');
    });

    Route::middleware(['permission:manage-lists'])->group(function () {
        Route::get('/lists', [ApiLeadListController::class, 'index'])->name('lists.index');
        Route::get('/lists/{list}', [ApiLeadListController::class, 'show'])->name('lists.show');
    });

    Route::middleware(['permission:export-leads', 'api.plan'])->group(function () {
        Route::post('/exports', [ApiExportController::class, 'store'])->name('exports.store');
        Route::get('/exports/{export}', [ApiExportController::class, 'show'])->name('exports.show');
        Route::get('/exports/{export}/download', [ApiExportController::class, 'download'])->name('exports.download');
    });
});
