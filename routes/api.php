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

Route::group([
    'middleware' => ['wantjson'],
], function() {
    // Login
    Route::post('login', 'AuthController@login')->name('login');

    Route::group([
        'middleware' => ['auth:api'],
    ], function() {
        // Logout
        Route::delete('logout', 'AuthController@logout')->name('logout');

        // Profile
        Route::get('profile', 'AuthController@profile')->name('profile');

        // Config
        Route::get('configs', 'ConfigController@index')->name('configs.index');
        Route::post('configs/update', 'ConfigController@update')->name('configs.update');

        // User
        Route::resource('users', 'UserController')->only([
            'index', 'show', 'store', 'update', 'destroy',
        ]);

        // Post
        Route::resource('posts', 'PostController')->only([
            'index', 'show', 'store', 'update', 'destroy',
        ]);
        // Post markdown to html
        Route::post('posts/markdown', 'PostController@markdown')->name('posts.markdown');

        // Term
        Route::resource('terms', 'TermController')->only([
            'index', 'show', 'store', 'update', 'destroy',
        ]);
    });
});
