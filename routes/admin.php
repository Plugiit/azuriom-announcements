<?php

use Azuriom\Plugin\PlugiitAnnouncements\Controllers\Admin\AnnouncementController;
use Azuriom\Plugin\PlugiitAnnouncements\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:plugiit-announcements.admin')->group(function () {
    Route::get('/', fn () => to_route('plugiit-announcements.admin.announcements.index'))->name('index');

    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');

    Route::resource('announcements', AnnouncementController::class)->except('show');
});
