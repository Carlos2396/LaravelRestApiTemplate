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

/**
 * Authentication routes
 */
Route::post('login', 'API\Auth\AuthController@login')->name('login');
Route::group(['middleware' => 'auth:api'], function() { 
    Route::get('logout', 'API\Auth\AuthController@logout')->name('logout');
});


/**
 * Articles routes
 */
Route::get('articles', 'ArticleController@index')->name('articles.list'); 
Route::post('articles', 'ArticleController@store')->name('articles.store');
Route::put('articles/{article}', 'ArticleController@update')->name('articles.update');
Route::delete('articles/{article}', 'ArticleController@destroy')->name('articles.delete');
Route::get('articles/{article}', 'ArticleController@show')->name('articles.show');


/**
 * Checks for admin role
 */
Route::group(['middleware' => 'role:admin'], function () {       
    
});


Route::any('{catchAll}', function($route) {
    return response()->json(['message' => 'Not found '.$route], 404);
});