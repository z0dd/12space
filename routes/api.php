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
Route::middleware(['auth:api'])->group(function () {
    Route::get('accounts', 'Api\AccountController@show')->name('getAccounts')->middleware('auth:api');
    Route::get('accounts/{id}', 'Api\AccountController@get')->name('getAccount');

    Route::get('answers', 'Api\AnswerController@show')->name('getAnswers');
    Route::get('answers/{id}', 'Api\AnswerController@get')->name('getAnswer');

    Route::get('courses', 'Api\CourseController@show')->name('getCourses');
    Route::get('courses/{id}', 'Api\CourseController@get')->name('getCourse');

    Route::get('courses/statuses', 'Api\CourseStatusController@show')->name('getCoursesStatuses');
    Route::get('courses/statuses/{id}', 'Api\CourseStatusController@get')->name('getCourseStatus');

    Route::get('lessons', 'Api\LessonController@show')->name('getLessons');
    Route::get('lessons/{id}', 'Api\LessonController@get')->name('getLesson');

    Route::get('lessons/contents', 'Api\LessonContentController@show');
    Route::get('lessons/contents/{id}', 'Api\LessonContentController@get');

    Route::get('lessons/types', 'Api\LessonTypeController@show');
    Route::get('lessons/types/{id}', 'Api\LessonTypeController@get');

    Route::get('history', 'Api\LoggerController@show');
    Route::get('history/{id}', 'Api\LoggerController@get');

    Route::get('modules', 'Api\ModuleController@show');
    Route::get('modules/{id}', 'Api\ModuleController@get');

    Route::get('questions', 'Api\QuestionController@show');
    Route::get('questions/{id}', 'Api\QuestionController@get');

    Route::get('tests', 'Api\TestController@show');
    Route::get('tests/{id}', 'Api\TestController@get');

    Route::get('users', 'Api\UserController@show')->middleware('auth:api');
    Route::get('users/{id}', 'Api\UserController@get');
    Route::post('users/{user_id}/answers/{answer_id}', 'Api\UserController@saveAnswer');
    Route::get('users/{user_id}/lessons/current', 'Api\UserController@getCurrentLesson');
    Route::get('users/{user_id}/lessons', 'Api\UserController@getLessons');
});
