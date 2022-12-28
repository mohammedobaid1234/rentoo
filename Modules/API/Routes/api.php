<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/api', function (Request $request) {
    return $request->user();
});

Route::post('/signUp', 'UserController@signUp');
Route::post('/login', 'UserController@login');
Route::post('/verifyCode', 'UserController@verifyCode');
Route::post('/sendCodeToApi', 'UserController@sendCodeToApi');
Route::post('/changePassword', 'UserController@changePassword');
Route::post('/logout', 'UserController@logout');


Route::get('/getTags', 'AppController@getTags');
Route::post('/addTagsToUser', 'AppController@addTagsToUser');
Route::get('/homePage', 'AppController@homePage');
Route::get('/profile', 'AppController@profile');
Route::post('/changePassWhenLogin', 'AppController@changePassWhenLogin');
Route::post('/updateProfile', 'AppController@updateProfile');
Route::post('/addImage', 'AppController@addImage');