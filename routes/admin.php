<?php

use Azuriom\Plugin\PlugiitLiveMap\Controllers\Admin\MapController;
use Azuriom\Plugin\PlugiitLiveMap\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('can:plugiit-live-map.admin')->group(function () {
    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');

    Route::post('/maps/test', [MapController::class, 'test'])->name('maps.test');
    Route::resource('maps', MapController::class)->except('show');
});
