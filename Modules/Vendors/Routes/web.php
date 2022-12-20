<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function() {
    Route::prefix('vendors')->group(function() {
        Route::get('manage', 'VendorsController@manage');
        Route::get('map', 'VendorsController@map');
        Route::get('storeLocation', 'VendorsController@storeLocation');
        Route::get('getVendorLocation/{id}', 'VendorsController@getVendorLocation');
        Route::get('datatable', 'VendorsController@datatable');
    });
    Route::resource('vendors', VendorsController::class);

    Route::prefix('type_of_vendors')->group(function() {
        Route::get('manage', 'TypeOFVendorController@manage');
        Route::get('datatable', 'TypeOFVendorController@datatable');
    });
    Route::resource('type_of_vendors', TypeOFVendorController::class);

    Route::prefix('times_label')->group(function() {
        Route::get('manage', 'TimesLabelController@manage');
        Route::get('datatable', 'TimesLabelController@datatable');
    });
    Route::resource('times_label', TimesLabelController::class);
});
