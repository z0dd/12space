<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Exceptions\ApiException;
use App\PassedTest;
use App\Question;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Users")
 * @OA\Get(
 *      path="/users",
 *      tags={"Users"},
 *      description="Get list of all models without any definitions",
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         ),
 *       ),
 * )
 * @OA\Get(
 *      path="/users/{id}",
 *      tags={"Users"},
 *      description="Returns model with all default definitions",
 *      @OA\Parameter(
 *          description="ID of model",
 *          in="path",
 *          name="id",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64",
 *         )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 * @OA\Post(
 *     path="users/{user_id}/answers/{answer_id}",
 *     tags={"Users"},
 *     description="Save user answer",
 *      @OA\Parameter(
 *          description="ID of user model",
 *          in="path",
 *          name="user_id",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64",
 *         )
 *      ),
 *      @OA\Parameter(
 *          description="ID of answer model",
 *          in="path",
 *          name="answer_id",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64",
 *         )
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/PassedTest")
 *      ),
 *     @OA\Response(response=404, description="Answer or user not found"),
 *     @OA\Response(response=400, description="Already answered"),
 *     @OA\Response(response=500, description="Error while saving answer"),
 * )
 * @OA\Get(
 *      path="/users/{user_id}/lessons/current",
 *      tags={"Users"},
 *      description="Return current question for user",
 *      @OA\Parameter(
 *          description="ID of user model",
 *          in="path",
 *          name="user_id",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64",
 *         )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Lesson")
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 *
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

    public function getCurrentLesson(\Illuminate\Http\Request $request, int $user_id)
    {
        $user = User::findOrFail($user_id);

        return $user->currentLesson();
    }
}
