<?php

use App\Http\Controllers\RepairRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    $paths = array_merge(['/', '/about', '/services', '/contact'], array_column(config('repair.services'), 'url'));

    return response()->view('sitemap', ['paths' => array_unique($paths)])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
});

Route::inertia('/', 'Home');
Route::inertia('/about', 'About');
Route::inertia('/services', 'Services');
Route::get('/contact', [RepairRequestController::class, 'create'])->name('contact');
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

Route::post('/contact', [RepairRequestController::class, 'store'])
    ->middleware('throttle:service-requests')->name('contact.store');
