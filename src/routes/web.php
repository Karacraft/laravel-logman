<?php

use Karacraft\Logman\Http\Controllers\LogmanController;

Route::name('logman.')->prefix('logman/')->group(function(){
    Route::get('master',[ LogmanController::class, 'getMasterData'])->name('master');
    Route::get('/',[ LogmanController::class, 'index'])->name('index');
});

