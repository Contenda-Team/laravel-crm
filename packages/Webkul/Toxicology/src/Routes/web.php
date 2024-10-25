<?php

use Illuminate\Support\Facades\Route;
use Toxicology\Http\Controllers\ToxicologyController;

Route::prefix('toxicology')->group(function () {
    Route::get('', [ToxicologyController::class, 'index'])->name('admin.toxicology.index');
});
