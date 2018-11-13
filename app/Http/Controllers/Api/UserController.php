<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Exceptions\ApiException;
use App\Lesson;
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
 *      security={
 *          {"passport": {}},
 *      },
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
 *      security={
 *          {"passport": {}},
 *      },
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
 *      security={
 *          {"passport": {}},
 *      },
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
 *
 * @OA\Get(
 *      path="/users/{user_id}/lessons/",
 *      tags={"Users"},
 *      description="Return all lessons",
 *      security={
 *          {"passport": {}},
 *      },
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
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Lesson")
 *          ),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 *
 * @OA\Get(
 *      path="/users/{user_id}/lessons/current",
 *      tags={"Users"},
 *      description="Return current lesson for user",
 *      security={
 *          {"passport": {}},
 *      },
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

        if ($passedTest->test->lesson->template) {
            $passedTest->test->lesson->template->sendNotify();
        }

        return $passedTest->fresh(['question','answer','test']);
    }

    /**
     * @param Request $request
     * @param int $user_id
     * @return mixed
     */
    public function getCurrentLesson(\Illuminate\Http\Request $request, int $user_id)
    {
        $user = User::findOrFail($user_id);

        return $user->currentLesson();
    }

    /**
     * @param Request $request
     * @param int $user_id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|static|static[]
     */
    public function getLessons(\Illuminate\Http\Request $request, int $user_id)
    {
        $user = User::with('passedTests.test.lesson')->findOrFail($user_id);
        $lessons = Lesson::withDefaultRelations()->get();

        foreach ($lessons as $lesson) {
            dd($lesson->template->sendNotify($user));
            $lesson->attachStatus($user);
        }

        return $lessons;
    }
}
