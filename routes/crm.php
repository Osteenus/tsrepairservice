<?php

use App\Http\Controllers\Crm\ApplianceController;
use App\Http\Controllers\Crm\CustomerController;
use App\Http\Controllers\Crm\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('crm')
    ->middleware(['private', 'auth:sanctum', config('jetstream.auth_session'), 'two-factor'])
    ->group(function () {
        // Named "dashboard" because Fortify/Jetstream redirect there after login.
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::name('crm.')->group(function () {
            Route::resource('customers', CustomerController::class)->except('destroy');
            Route::post('customers/{customer}/appliances', [ApplianceController::class, 'store'])->name('appliances.store');
            Route::put('appliances/{appliance}', [ApplianceController::class, 'update'])->name('appliances.update');
        });
    });
