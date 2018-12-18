<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


Route::group(['namespace' => 'API'], function() {
    /**
     * Authentication routes
     */
    Route::group(['namespace' => 'Auth'], function() {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('register', 'RegisterController@register')->name('register');
        
        // Forget password routes
        Route::post('password/create', 'PasswordResetController@create')->name('password.reset.create');
        Route::post('password/reset', 'PasswordResetController@reset')->name('password.reset');

        // Email confirmation routes
        Route::get('verify/{uuid}', 'AccountVerificationController@verifyAccount')->name('verification');
        Route::get('resend/{email}', 'AccountVerificationController@resendVerificationEmail')->name('verification.resend');

        Route::group(['middleware' => 'auth:api'], function() { 
            Route::get('logout', 'AuthController@logout')->name('logout');
        });
    });

    Route::group(['middleware' => 'auth:api'], function() { 
        /**
         * Article routes
         */
        Route::get('articles', 'ArticleController@index')->name('articles.list'); 
        Route::post('articles', 'ArticleController@store')->name('articles.store');
        Route::get('articles/{article}', 'ArticleController@show')->name('articles.show');
        Route::put('articles/{article}', 'ArticleController@update')->middleware('can:update-resource,article')->name('articles.update');
        Route::delete('articles/{article}', 'ArticleController@destroy')->middleware('can:update-resource,article')->name('articles.delete');
    });
});

/**
 * Test routes, do not delete
 */
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('test/check', 'API\Auth\AuthController@check')->name('auth.check');

    Route::group(['middleware' => 'role:admin'], function () {       
        Route::get('test/admin/check', function() {
            return response(['success' => true], 200);
        })->name('admin.check');
    });
});

Route::any('{catchAll}', function($route) {
    return response()->json(['message' => 'Not found '.$route], 404);
});