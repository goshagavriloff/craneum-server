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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/signup','UserPhotoController@signup');
Route::post('/login','UserPhotoController@login');
Route::post('/logout','UserPhotoController@logout');
Route::post('/user','UserPhotoController@user');
Route::post('/user/{ID}/share','UserPhotoController@share');

Route::post('/photo','PhotoController@create');
Route::post('/photo/{ID}','PhotoController@update');
Route::get('/photo','PhotoController@show');
Route::get('/photo/{ID}','PhotoController@index');
Route::delete('/photo/{ID}','PhotoController@destroy');
