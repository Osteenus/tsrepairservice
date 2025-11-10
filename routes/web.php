<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Home');
Route::inertia('/about', 'About');
Route::inertia('/services', 'Services');
Route::inertia('/contact', 'Contact');
Route::inertia('/extra', 'Extra');
