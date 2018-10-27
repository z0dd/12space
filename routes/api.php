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

Route::get('accounts', 'Api\AccountController@show')->name('getAccounts');
Route::get('accounts/{id}', 'Api\AccountController@get')->name('getAccount');
Route::post('accounts/{id}', 'Api\AccountController@set')->name('setAccount');
Route::post('accounts', 'Api\AccountController@create')->name('createAccount');

Route::get('answers', 'Api\AnswerController@show')->name('getAnswers');
Route::get('answers/{id}', 'Api\AnswerController@get')->name('getAnswer');
Route::post('answers/{id}', 'Api\AnswerController@set')->name('setAnswer');
Route::post('answers', 'Api\AnswerController@create')->name('createAnswer');


