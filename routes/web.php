<?php

use App\Http\Controllers\RepairRequestController;
use App\Mail\WebFormRequestReceivedEmail;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Home');
Route::inertia('/about', 'About');
Route::inertia('/services', 'Services');
Route::inertia('/contact', 'Contact');
Route::inertia('/extra', 'Extra');

Route::inertia('/services/refrigerator-repair-moorpark', 'Services/RefrigeratorRepairService');
Route::inertia('/washer-repair-moorpark', 'Services/WasherRepairService')->name('services.washer');
Route::permanentRedirect('/services/washer-repair', '/washer-repair-moorpark');
Route::inertia('/services/dryer-repair', 'Services/DryerRepairService');
Route::inertia('/services/oven-repair', 'Services/OvenRepairService');
Route::inertia('/services/dishwasher-repair', 'Services/DishwasherRepairService');
Route::inertia('/services/stove-range-repair', 'Services/StoveRangeRepairService');
Route::inertia('/services/microwave-repair', 'Services/MicrowaveRepairService');
Route::inertia('/services/range-hood-repair', 'Services/RangeHoodRepairService');
Route::inertia('/services/trash-compactor-repair', 'Services/TrashCompactorRepairService');
Route::inertia('/services/electronic-repair', 'Services/ElectronicRepairService');

Route::post('/contact', [RepairRequestController::class, 'store'])->name('contact.store');

Route::get('/send-request-received-email', function () {
    Mail::to('ilmetal44@gmail.com')->send(new WebFormRequestReceivedEmail());
    return 'Test email sent!';
});
