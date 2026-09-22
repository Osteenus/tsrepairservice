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
Route::permanentRedirect('/washer-repair', '/washer-repair-moorpark');
Route::inertia('/services/dryer-repair', 'Services/DryerRepairService');
Route::inertia('/oven-stove-repair-moorpark', 'Services/OvenRepairService')->name('services.oven-stove');
Route::permanentRedirect('/services/oven-repair', '/oven-stove-repair-moorpark');
Route::inertia('/dishwasher-repair-moorpark', 'Services/DishwasherRepairService')->name('services.dishwasher');
Route::permanentRedirect('/services/dishwasher-repair', '/dishwasher-repair-moorpark');
Route::permanentRedirect('/services/stove-range-repair', '/oven-stove-repair-moorpark');
Route::inertia('/microwave-repair-moorpark', 'Services/MicrowaveRepairService')->name('services.microwave');
Route::permanentRedirect('/services/microwave-repair', '/microwave-repair-moorpark');
Route::inertia('/range-hood-repair-moorpark', 'Services/RangeHoodRepairService')->name('services.range-hood');
Route::permanentRedirect('/services/range-hood-repair', '/range-hood-repair-moorpark');
Route::inertia('/trash-compactor-repair-moorpark', 'Services/TrashCompactorRepairService')->name('services.trash-compactor');
Route::permanentRedirect('/services/trash-compactor-repair', '/trash-compactor-repair-moorpark');
Route::inertia('/services/electronic-repair', 'Services/ElectronicRepairService');

Route::post('/contact', [RepairRequestController::class, 'store'])->name('contact.store');

Route::get('/send-request-received-email', function () {
    Mail::to('ilmetal44@gmail.com')->send(new WebFormRequestReceivedEmail());
    return 'Test email sent!';
});
