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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Веб-хук для обратной связи сендгрида
Route::any('/webhooks/sendgrid', 'WebhookController@sendgrid');

// Оплата через Яндекс Кассу
Route::any('/payment', 'PaymentController@payment');
Route::any('/payment_result/{return_id}', 'PaymentController@paymentResult');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
