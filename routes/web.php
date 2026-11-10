<?php
// This file is loaded by the package service provider for local development testing.
// In production, the host application should define its own routes at /logman or similar.

use Illuminate\Support\Facades\Route;

if (app()->runningInWeb()) {
    Route::middleware(['auth'])->group(function () {
        Route::get('/logman', [Karacraft\Logman\Http\Controllers\LogController::class, 'index'])
            ->name('logman.index');
    });
}
