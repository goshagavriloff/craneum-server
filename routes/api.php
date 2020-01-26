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
Route::post('/user/{id}/share','UserPhotoController@share');

Route::post('/photo','UserPhotoController@create');
Route::post('/photo/{id}','UserPhotoController@update');
Route::get('/photo','UserPhotoController@show');
Route::get('/photo/{id}','UserPhotoController@index');
Route::delete('/photo/{id}','UserPhotoController@destroy');
