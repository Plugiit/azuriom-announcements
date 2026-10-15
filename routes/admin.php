<?php

use Azuriom\Plugin\Announcements\Controllers\Admin\AnnouncementController;
use Azuriom\Plugin\Announcements\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:announcements.admin')->group(function () {
    Route::get('/', [AnnouncementController::class, 'index'])->name('index');

    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');

    Route::resource('announcements', AnnouncementController::class)->except('show');
});
