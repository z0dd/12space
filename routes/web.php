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

// Получение контента по маскированной ссылке
Route::get('/content/{content_id}/{content_index}', function (int $content_id, int $content_index) {

    $file = \App\LessonContent::where('file_type_id',2)->findOrFail($content_id);

    $files = json_decode($file->file,1);

    if (is_null($files) || empty($files[$content_index]['download_link'])) {
        abort(404);
    }

    return Storage::disk('public')->download($files[$content_index]['download_link']);

})->name('content_download')->middleware('auth');

//Route::get('/test', 'HomeController@test')->name('test');
