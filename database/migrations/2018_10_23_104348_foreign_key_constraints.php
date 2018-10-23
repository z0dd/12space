<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loggers', function (Blueprint $table) {
            $table->foreign('log_type_id')->references('id')->on('logger_types')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('gender_id')->references('id')->on('genders')->onUpdate('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade');
        });

        Schema::table('passed_tests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('tests')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('answers')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('test_id')->references('id')->on('tests')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->foreign('lesson_id')->references('id')->on('lessons')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('lesson_types')->onUpdate('cascade');
            $table->foreign('gender_id')->references('id')->on('genders')->onUpdate('cascade');
            $table->foreign('template_id')->references('id')->on('templates')->onUpdate('cascade');
        });

        Schema::table('lesson_contents', function (Blueprint $table) {
            $table->foreign('lesson_id')->references('id')->on('lessons')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('gender_id')->references('id')->on('genders')->onUpdate('cascade');
            $table->foreign('file_type_id')->references('id')->on('file_types')->onUpdate('cascade');
        });

        Schema::table('modules_to_lessons', function (Blueprint $table) {
            $table->foreign('lesson_id')->references('id')->on('lessons')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('status_id')->references('id')->on('course_statuses')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loggers', function (Blueprint $table) {
            $table->dropForeign(['log_type_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['gender_id']);
            $table->dropForeign(['account_id']);
        });

        Schema::table('passed_tests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['test_id']);
            $table->dropForeign(['question_id']);
            $table->dropForeign(['answer_id']);
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['test_id']);
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropForeign(['gender_id']);
            $table->dropForeign(['template_id']);
        });

        Schema::table('lesson_contents', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['gender_id']);
            $table->dropForeign(['file_type_id']);
        });

        Schema::table('modules_to_lessons', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['module_id']);
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
        });
    }
}
