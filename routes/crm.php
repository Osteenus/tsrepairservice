<?php

use App\Http\Controllers\Crm\ApplianceController;
use App\Http\Controllers\Crm\CustomerController;
use App\Http\Controllers\Crm\DashboardController;
use App\Http\Controllers\Crm\DocumentController;
use App\Http\Controllers\Crm\EstimateController;
use App\Http\Controllers\Crm\InvoiceController;
use App\Http\Controllers\Crm\PhotoController;
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

            Route::post('jobs/{job}/photos', [PhotoController::class, 'store'])->name('photos.store');
            Route::get('photos/{photo}', [PhotoController::class, 'show'])->name('photos.show');
            Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

            Route::post('jobs/{job}/estimates', [EstimateController::class, 'store'])->name('estimates.store');
            Route::post('documents/{document}/issue', [EstimateController::class, 'issue'])->name('estimates.issue');
            Route::get('documents/{document}/present', [EstimateController::class, 'present'])->name('estimates.present');
            Route::post('documents/{document}/approve', [EstimateController::class, 'approve'])->name('estimates.approve');
            Route::post('documents/{document}/revise', [EstimateController::class, 'revise'])->name('estimates.revise');

            Route::post('jobs/{job}/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
            Route::post('documents/{document}/issue-invoice', [InvoiceController::class, 'issue'])->name('invoices.issue');
            Route::get('documents/{document}/sign-invoice', [InvoiceController::class, 'present'])->name('invoices.present');
            Route::post('documents/{document}/sign-invoice', [InvoiceController::class, 'sign'])->name('invoices.sign');
            Route::post('documents/{document}/payments', [InvoiceController::class, 'storePayment'])->name('payments.store');
            Route::delete('payments/{payment}', [InvoiceController::class, 'destroyPayment'])->name('payments.destroy');

            Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
            Route::post('documents/{document}/email', [DocumentController::class, 'email'])->name('documents.email');
            Route::get('documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
            Route::put('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
            Route::post('documents/{document}/void', [DocumentController::class, 'void'])->name('documents.void');
            Route::get('documents/{document}/preview', [DocumentController::class, 'preview'])->name('documents.preview');
            Route::get('documents/{document}/pdf', [DocumentController::class, 'pdf'])->name('documents.pdf');
            Route::get('signatures/{signature}', [DocumentController::class, 'signature'])->name('signatures.show');
        });
    });
