<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home
Route::get('/', 'SiteController@welcome')->name('home');

// Backend
Route::get('/backend.html', 'SiteController@backend')->name('backend');

// Search
Route::get('/search.html', 'SiteController@search')->name('site.search');

// Sitemap
Route::get('/sitemap.xml', 'SiteController@sitemap')->name('site.sitemap');

// Bing Site Auth
Route::get('/BingSiteAuth.xml', 'SiteController@bingSiteAuth')->name('site.bingsiteauth');

// Google Verification
Route::get('/google{id}.html', 'SiteController@googleVerification')->where('id', '\w+')->name('site.googleverification');

// Post
Route::get('/post/{slug}.html', 'SiteController@postShow')->name('post.show');

// Term
Route::get('/categories.html', 'SiteController@termCats')->name('term.cats');
Route::get('/category/{slug}.html', 'SiteController@termCatShow')->name('term.cat.show');
Route::get('/tags.html', 'SiteController@termTags')->name('term.tags');
Route::get('/tag/{slug}.html', 'SiteController@termTagShow')->name('term.tag.show');
