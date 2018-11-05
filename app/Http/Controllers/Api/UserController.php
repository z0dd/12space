<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Exceptions\ApiException;
use App\PassedTest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserController extends ApiSpaceController
{
    /**
     * UserController constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param Request $request
     * @param int $user_id
     * @param int $answer_id
     * @return null|static
     * @throws ApiException
     */
    public function saveAnswer(\Illuminate\Http\Request $request, int $user_id, int $answer_id)
    {
        $answer = Answer::with(['question'])->findOrFail($answer_id);

        $passedTest = new PassedTest([
            'user_id' => $user_id,
            'test_id' => $answer->question->test_id,
            'question_id' => $answer->question->id,
        ]);

        if ($passedTest->exists()) {
            throw new ApiException('Already answered', 400);
        }

        $passedTest->answer_id = $answer->id;

        if (false == $passedTest->save()) {
            throw new ApiException('Not saved', 500);
        }

        return $passedTest->fresh(['question','answer','test']);
    }
}
