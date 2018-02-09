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



Route::get('/shoutbox','ShoutboxController@index');
Route::get('/shoutbox/{id}','ShoutboxController@show');

Route::post('/shoutbox','ShoutboxController@store');


// 404 Route
Route::fallback(function(){
    return response()->json(['message' => 'Not Found!'], 404);
});