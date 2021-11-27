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
    Route::get('/articles', 'ArticlesController@index')->with('cors'); // get all articles
    Route::post('/articles', 'ArticlesController@create')->with('cors');; // create article
    Route::post('/search', 'SearchController@index')->with('cors');; // search
});

