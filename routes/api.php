<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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
URL::forceScheme('https');
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/articles', 'ArticlesController@index'); // get all articles
    Route::post('/articles', 'ArticlesController@create');; // create article
    Route::post('/search', 'SearchController@index');; // search
});

