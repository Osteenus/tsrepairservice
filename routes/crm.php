<?php

use App\Http\Controllers\Crm\ApplianceController;
use App\Http\Controllers\Crm\CustomerController;
use App\Http\Controllers\Crm\DashboardController;
use App\Http\Controllers\Crm\RepairRequestController;
use App\Http\Controllers\Crm\WorkOrderController;
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

            Route::get('requests', [RepairRequestController::class, 'index'])->name('requests.index');
            Route::get('requests/{repairRequest}', [RepairRequestController::class, 'show'])->name('requests.show');
            Route::post('requests/{repairRequest}/convert', [RepairRequestController::class, 'convert'])->name('requests.convert');
            Route::post('requests/{repairRequest}/archive', [RepairRequestController::class, 'archive'])->name('requests.archive');

            Route::resource('jobs', WorkOrderController::class)->except('destroy');
            Route::patch('jobs/{job}/status', [WorkOrderController::class, 'status'])->name('jobs.status');
        });
    });
