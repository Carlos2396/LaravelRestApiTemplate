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

Route::post('login', 'API\Auth\AuthController@login')->name('login');

Route::get('articles', 'ArticleController@index');
// Route::get('articles/{article}', 'ArticleController@show');
Route::post('articles', 'ArticleController@store');
Route::put('articles/{article}', 'ArticleController@update');
Route::delete('articles/{article}', 'ArticleController@delete');

Route::group(['middleware' => 'auth:api'], function() {

    Route::group(['middleware' => 'role:admin'], function () {
        Route::get('articles/{article}', 'ArticleController@show');
    });
    
    Route::get('logout', 'API\Auth\AuthController@logout')->name('logout');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
