<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Case\CaseController;

Route::controller(CaseController::class)->prefix('cases')->group(function () {
    Route::get('', 'index')->name('admin.cases.index');

    Route::get('create/{id?}', 'create')->name('admin.cases.create');

    Route::post('create', 'store')->name('admin.cases.store');

    Route::get('edit/{id?}', 'edit')->name('admin.cases.edit');

    Route::put('edit/{id}', 'update')->name('admin.cases.update');

    Route::get('print/{id?}', 'print')->name('admin.cases.print');

    Route::delete('{id}', 'destroy')->name('admin.cases.delete');

    Route::get('search', 'search')->name('admin.cases.search');

    Route::post('mass-destroy', 'massDestroy')->name('admin.cases.mass_delete');
});


