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

Route::get('courses', 'Api\CourseController@show')->name('getCourses');
Route::get('courses/{id}', 'Api\CourseController@get')->name('getCourse');
Route::post('courses/{id}', 'Api\CourseController@set')->name('setCourse');
Route::post('courses', 'Api\CourseController@create')->name('createCourse');

Route::get('courses/statuses', 'Api\CourseStatusController@show')->name('getCoursesStatuses');
Route::get('courses/statuses/{id}', 'Api\CourseStatusController@get')->name('getCourseStatus');
Route::post('courses/statuses/{id}', 'Api\CourseStatusController@set')->name('setCourseStatus');
Route::post('courses/statuses', 'Api\CourseStatusController@create')->name('createCourseStatus');

Route::get('lessons', 'Api\LessonController@show')->name('getLessons');
Route::get('lessons/{id}', 'Api\LessonController@get')->name('getLesson');
Route::post('lessons/{id}', 'Api\LessonController@set')->name('setLesson');
Route::post('lessons', 'Api\LessonController@create')->name('createLesson');

Route::get('lessons/contents', 'Api\LessonContentController@show');
Route::get('lessons/contents/{id}', 'Api\LessonContentController@get');
Route::post('lessons/contents/{id}', 'Api\LessonContentController@set');
Route::post('lessons/contents', 'Api\LessonContentController@create');

Route::get('lessons/types', 'Api\LessonTypeController@show');
Route::get('lessons/types/{id}', 'Api\LessonTypeController@get');
Route::post('lessons/types/{id}', 'Api\LessonTypeController@set');
Route::post('lessons/types', 'Api\LessonTypeController@create');

Route::get('history', 'Api\LoggerController@show');
Route::get('history/{id}', 'Api\LoggerController@get');
Route::post('history/{id}', 'Api\LoggerController@set');
Route::post('history', 'Api\LoggerController@create');

Route::get('modules', 'Api\ModuleController@show');
Route::get('modules/{id}', 'Api\ModuleController@get');
Route::post('modules/{id}', 'Api\ModuleController@set');
Route::post('modules', 'Api\ModuleController@create');

Route::get('questions', 'Api\QuestionController@show');
Route::get('questions/{id}', 'Api\QuestionController@get');
Route::post('questions/{id}', 'Api\QuestionController@set');
Route::post('questions', 'Api\QuestionController@create');

Route::get('tests', 'Api\TestController@show');
Route::get('tests/{id}', 'Api\TestController@get');
Route::post('tests/{id}', 'Api\TestController@set');
Route::post('tests', 'Api\TestController@create');

Route::get('users', 'Api\UserController@show');
Route::get('users/{id}', 'Api\UserController@get');
Route::post('users/{id}', 'Api\UserController@set');
Route::post('users', 'Api\UserController@create');

