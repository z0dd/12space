<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Exceptions\ApiException;
use App\Http\Requests\SaveUserRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Lesson;
use App\PassedTest;
use App\PasswordReminder;
use App\SendgridNotification;
use App\Template;
use App\User;
use App\UserHashAuth;
use App\UserToCourse;
use App\UserToModule;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Password;

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
     * @return PassedTest
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

        $passedTest = $passedTest->fresh(['question','answer','test']);

        // Если пришедший ответ на последний вопрос теста, то отправляем письмо об успешном прохождении
        if ($passedTest->test->getLastQuestion()->id == $answer->question->id) {
            $passedTest->test->lesson->template->sendNotify(User::find($user_id));
        }

        return $passedTest;
    }

    /**
     * @param Request $request
     * @param int $user_id
     * @return mixed
     */
    public function getCurrentLesson(\Illuminate\Http\Request $request, int $user_id)
    {
        $user = User::findOrFail($user_id);

        return $user->currentLesson()->attachPublish($user);
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
     *
     * @return array
     * @throws ApiException
     * @throws \Exception
     * @throws \SendGrid\Mail\TypeException
     *
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
                $user = User::whereEmail($credentials['email'])->with('userHash')->first();
                if (
                    false == is_null($user) && $user instanceof User
                ) {
                    $userHash = UserHashAuth::find($user->id);
                    if (false == is_null($userHash)) {
                        $userHash->delete();
                    }
                }

                $token = app(PasswordBroker::class)->createToken($user);

                $template = Template::whereName('ResetPassword')->firstOrFail();

                $email = new \SendGrid\Mail\Mail();
                $email->setFrom(config('mail.from.address'),config('mail.from.name'));
                $email->addTo($user->email,$user->full_name);
                $email->setTemplateId($template->getTemplateId());
                $email->addDynamicTemplateData('recoverLink',route('password.reset',$token));
                $email->addDynamicTemplateData('token',$token);

                try {
                    $response = (new \SendGrid(env('SENDGRID_API_KEY')))->send($email);
                } catch (Exception $e) {
                    throw new ApiException('Message not sended. Sendgrid error', 500);
                }

                if ($response->statusCode() !== 202) {
                    throw new ApiException('Sendgrid return error', 500);
                }

                SendgridNotification::processSendGridRespose($response, $template, $user);

                return [
                    "status" => "success",
                    'text'=> "Message sended",
                ];
            case Password::INVALID_USER:
                throw new ApiException('Invalid user', 403);
        }

        throw new ApiException('Message not sended', 500);
    }

    /**
     * @param UserResetPasswordRequest $request
     *
     * @return mixed
     * @throws ApiException
     *
     * @OA\Post(
     *      path="/users/reset",
     *      tags={"Users"},
     *      description="Reset User password",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="Token",
     *          in="query",
     *          name="token",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *         )
     *      ),
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
     *     @OA\Parameter(
     *          description="User's password",
     *          in="query",
     *          name="password",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              minimum="6",
     *              maximum="20",
     *              format="password"
     *         )
     *      ),
     *     @OA\Parameter(
     *          description="Password confirmation",
     *          in="query",
     *          name="password_confirmation",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="password"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="invalid request"
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="Error while sending message"
     *       ),
     * )
     */
    public function resetUserPassword(UserResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $remider = PasswordReminder::with('user')->findOrFail($validated['email']);

        if (false == Hash::check($validated['token'],$remider->token)) {
            throw new ApiException('Wrong token',401);
        }

        $user = User::where('email',$validated['email'])->firstOrFail();
        $user->password = bcrypt($validated['password']);
        try {
            $user->save();
        } catch (\Exception $exception) {
            throw new ApiException('Reset password Error', 500);
        }

        return $user;
    }

    /**
     * @param UserRegisterRequest $request
     *
     * @return mixed
     * @throws ApiException
     *
     *
     * @OA\Post(
     *      path="/users/register",
     *      tags={"Users"},
     *      description="Register new User",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="User's name",
     *          in="query",
     *          name="name",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              minimum="3",
     *              maximum="254"
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="User's last name",
     *          in="query",
     *          name="last_name",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              minimum="3",
     *              maximum="254"
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="User's phone",
     *          in="query",
     *          name="phone",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              pattern="^((7)+([0-9]){10})$"
     *         )
     *      ),
     *     @OA\Parameter(
     *          description="User's gender ID",
     *          in="query",
     *          name="gender_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              minimum="1"
     *         )
     *      ),
     *     @OA\Parameter(
     *          description="User's account ID",
     *          in="query",
     *          name="account_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              minimum="1"
     *         )
     *      ),
     *     @OA\Parameter(
     *          description="User's password",
     *          in="query",
     *          name="password",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              minimum="6",
     *              maximum="20",
     *              format="password"
     *         )
     *      ),
     *     @OA\Parameter(
     *          description="Password confirmation",
     *          in="query",
     *          name="password_confirmation",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="password"
     *         )
     *      ),
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
     *          description="invalid request"
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="Error while sending message"
     *       ),
     * )
     */
    public function registerNewUser(UserRegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        DB::beginTransaction();
        try {
            $user = User::create($validated);

            UserToModule::create([
                'user_id' => $user->id,
                'module_id' => 1,
                'status' => UserToModule::STATUSES['available']
            ]);

            UserToCourse::create([
                'user_id' => $user->id,
                'course_id' => 1,
                'status' => UserToCourse::STATUSES['available']
            ]);

            $user = User::WithDefaultRelations()
                ->with(['courses', 'modules'])
                ->find($user->id);

            DB::commit();
        } catch (QueryException $exception) {
            DB::rollBack();
            if (strpos('users_email_unique',$exception->getMessage()) !== false) {
                throw new ApiException('User with this email already registered.', 400);
            }
            throw new ApiException('User data wrong.', 500);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ApiException('User cannot be register.', 500);
        }

        return $user;
    }
}
