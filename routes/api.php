<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/articles', 'ArticlesController@index'); // get all
//    Route::get('/test/{id}', 'CountriesController@indexId'); // get by id
    Route::post('/articles', 'ArticlesController@create'); // create
//    Route::put('/test/{id}', 'CountriesController@update'); // create | update by id
//    Route::delete('/test/{id}', 'CountriesController@destroy'); // delete
});

