<?php

namespace App\Http\Controllers;

use App\PassedTest;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     *
     */
    public function test()
    {
        /*$validated = [
            'name' => 'Тест',
            'last_name' => 'Тестов',
            'email' => 'z0dd_bucket@mail.ru',
            'phone' => '79098889988',
        ];

        if (empty($validated['password'])) {
            $validated['password'] = uniqid();
        }

        $user = new User($validated);*/

        return ['status' => PassedTest::first()->test->lesson->template->sendNotify(
            User::whereEmail('z0dd@mail.ru')->first()
        )];
    }
}
