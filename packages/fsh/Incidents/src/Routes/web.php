<?php

use Illuminate\Support\Facades\Route;
use fsh\Incidents\Http\Controllers\IncidentsController;

Route::prefix('incidents')->group(function () {
    Route::get('', [IncidentsController::class, 'index'])->name('admin.incidents.index');
});
