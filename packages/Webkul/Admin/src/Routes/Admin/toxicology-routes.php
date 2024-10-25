<?php

use Illuminate\Support\Facades\Route;
use YourVendor\Toxicology\Http\Controllers\ToxicologyController; // Adjust the namespace as needed

Route::controller(ToxicologyController::class)->prefix('toxicology')->group(function () {
    Route::get('', 'index')->name('admin.toxicology.index'); // List all toxicology records

    Route::get('create', 'create')->name('admin.toxicology.create'); // Show form to create a new record

    Route::post('create', 'store')->name('admin.toxicology.store'); // Store a new toxicology record

    Route::get('edit/{id}', 'edit')->name('admin.toxicology.edit'); // Show form to edit a record

    Route::put('edit/{id}', 'update')->name('admin.toxicology.update'); // Update a toxicology record

    Route::get('print/{id}', 'print')->name('admin.toxicology.print'); // Print a toxicology record

    Route::delete('{id}', 'destroy')->name('admin.toxicology.delete'); // Delete a toxicology record

    Route::get('search', 'search')->name('admin.toxicology.search'); // Search for toxicology records

    Route::post('mass-destroy', 'massDestroy')->name('admin.toxicology.mass_delete'); // Mass delete toxicology records
});
