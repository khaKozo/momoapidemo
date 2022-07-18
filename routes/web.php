<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@show')->name('sms');
Route::post('/', 'HomeController@storePhoneNumber')->name('store-message');
Route::post('/custom', 'HomeController@sendCustomMessage')->name('custom-message');

Route::get('/momo', 'MomoController@index')->name('momo');
Route::post('/momo', 'MomoController@store')->name('momo-post');
Route::post('/momo-list', 'MomoController@showListPayment')->name('show-list-payment');

Route::get('/momo-check', 'MomoController@checkTransaction')->name('momo-check');
Route::post('/momo-check', 'MomoController@postCheckTransaction')->name('momo-check-post');
