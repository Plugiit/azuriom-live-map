<?php

use Azuriom\Plugin\LiveMap\Controllers\LiveMapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LiveMapController::class, 'index'])->name('index');
Route::get('/{map}', [LiveMapController::class, 'show'])->name('show')->whereNumber('map');
