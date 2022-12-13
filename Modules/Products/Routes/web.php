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

Route::prefix('products')->as('Products.')->group(function() {
    Route::get('/manage', 'ProductsController@manage')->name('manage');
    Route::get('/product-info/{id}', 'ProductsController@showProduct')->name('showProduct');
    Route::get('/datatable', 'ProductsController@datatable')->name('datatable');
    Route::get('/attributes/categories/{id}', 'ProductsController@getAttribute')->name('getAttribute');
    Route::get('/tags/categories/{id}', 'ProductsController@getTags')->name('getTags');
    Route::post('/image-add', 'ProductsController@addImage')->name('image_add');
    Route::delete('/image-remove/{id}', 'ProductsController@removeImage')->name('image_remove');
    
});
Route::resource('products', ProductsController::class);

Route::prefix('category_attribute_types')->as('category_attribute_types.')->group(function() {
    Route::get('/manage', 'CategoryAttributeTypesController@manage')->name('manage');
    Route::get('/datatable', 'CategoryAttributeTypesController@datatable')->name('datatable');
    Route::get('/list/{id}', 'CategoryAttributeTypesController@list')->name('list');
});

Route::prefix('tags')->as('tags.')->group(function() {
    Route::get('/manage', 'TagsController@manage')->name('manage');
    Route::get('/datatable', 'TagsController@datatable')->name('datatable');
});
Route::resource('tags', TagsController::class);