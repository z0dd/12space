<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Exceptions\ApiException;
use App\Http\Requests\SaveUserRequest;
use App\Lesson;
use App\PassedTest;
use App\Question;
use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Password;
use SendGrid\Client;

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
 * @OA\Put(
 *     path="/users/{user_id}/answers/{answer_id}",
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
     * @param int     $user_id
     * @param int     $answer_id
     *
     * @return null|static
     * @throws ApiException
     */
    public function saveAnswer(\Illuminate\Http\Request $request, int $user_id, int $answer_id)
    {
        $answer = Answer::with(['question'])->findOrFail($answer_id);

        $passedTest = PassedTest::where('user_id', $user_id)
            ->where('test_id', $answer->question->test_id)
            ->where('question_id', $answer->question->id)
            ->first();

        if ($passedTest instanceof PassedTest) {
            throw new ApiException('Already answered', 400);
        }else{
            $passedTest = new PassedTest([
                'user_id' => $user_id,
                'test_id' => $answer->question->test_id,
                'question_id' => $answer->question->id,
            ]);
        }

        $passedTest->answer_id = $answer->id;

        if (false == $passedTest->save()) {
            throw new ApiException('Not saved', 500);
        }

        if ($passedTest->test->lesson->template) {
            $passedTest->test->lesson->template->sendNotify(User::find($user_id));
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
            $lesson->attachStatus($user);
        }

        return $lessons;
    }

    /**
     * @param SaveUserRequest $request
     * @param int $user_id
     * @return User
     * @throws ApiException
     *
     * @OA\Put(
     *      path="/users/{user_id}/",
     *      tags={"Users"},
     *      description="Save user props to db",
     *      security={
     *          {"passport": {}},
     *      },
     *
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
     *          description="User's name",
     *          in="query",
     *          name="name",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="User's last name",
     *          in="query",
     *          name="last_name",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="User's email address",
     *          in="query",
     *          name="email",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="email",
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="User's phone number",
     *          in="query",
     *          name="phone",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int32",
     *         )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/User")
     *          ),
     *       ),
     *      @OA\Response(response=404, description="Not found"),
     * )
     */
    public function saveUser(SaveUserRequest $request, int $user_id) : User
    {
        $user = User::find($user_id);

        $user->fill($request->validated());

        if (!$user->save()) {
            throw new ApiException("User data not saved", 500);
        }

        return $user;
    }

    /**
     * @param Request $request
     * @return array
     * @throws ApiException
     *
     * @OA\Put(
     *      path="/users/reset",
     *      tags={"Users"},
     *      description="Return current lesson for user",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="User's email",
     *          in="query",
     *          name="email",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="email",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="invalid user"
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="Error while sending message"
     *       ),
     * )
     */
    public function sendResetLinkEmail(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return [
                    "status" => "success",
                    'text'=> "Message sended",
                ];
            case Password::INVALID_USER:
                throw new ApiException('Invalid user', 403);
        }

        throw new ApiException('Message not sended', 500);
    }
}
