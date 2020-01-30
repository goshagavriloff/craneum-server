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
|https://thewebtier.com/laravel/handle-cors-requests-vuejs-client-laravel-api/
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});

*/

Route::middleware('validate_signup')->post('/signup','UserPhotoController@signup');
Route::middleware('validate_login')->post('/login','UserPhotoController@login');
Route::middleware('bearer_token')->post('/logout','UserPhotoController@logout');
Route::middleware('bearer_token')->get('/user','UserPhotoController@users');
Route::middleware('bearer_token')->post('/user/{ID}/share','UserPhotoController@share');

Route::middleware('bearer_token')->middleware('validate_load_photo')->post('/photo','PhotoController@create');
Route::middleware('bearer_token')->middleware('validate_update_photo')->patch('/photo/{ID}','PhotoController@update');
Route::middleware('bearer_token')->middleware('validate_update_photo')->post('/photo/{ID}','PhotoController@update');
Route::middleware('bearer_token')->get('/photo','PhotoController@show');
Route::middleware('bearer_token')->get('/photo/{ID}','PhotoController@index');
Route::middleware('bearer_token')->delete('/photo/{ID}','PhotoController@destroy');
