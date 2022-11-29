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

Route::prefix('categories')->as('categories.')->group(function() {
    Route::get('/manage', 'CategoriesController@manage')->name('manage');
    Route::get('/datatable', 'CategoriesController@datatable')->name('datatable');
    Route::post('/image-add', 'CategoriesController@addImage')->name('image_add');
    Route::delete('/image-remove/{id}', 'CategoriesController@removeImage')->name('image_remove');
    Route::get('/vendor-categories/{id}', 'CategoriesController@vendorCategories')->name('vendor_categories');
});
Route::resource('categories', CategoriesController::class);

Route::prefix('category_attribute_types')->as('category_attribute_types.')->group(function() {
    Route::get('/manage', 'CategoryAttributeTypesController@manage')->name('manage');
    Route::get('/datatable', 'CategoryAttributeTypesController@datatable')->name('datatable');
    Route::get('/list/{id}', 'CategoryAttributeTypesController@list')->name('list');
});
Route::resource('category_attribute_types', CategoryAttributeTypesController::class);