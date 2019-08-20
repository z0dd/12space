<?php

namespace App\Http\Controllers\Api;

use App\Gender;
use App\Http\Controllers\Controller;
use App\Lesson;
use App\PassedTest;
use App\Story;
use App\StoryContent;
use App\StoryContentToUser;
use App\Test;
use App\User;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations\Get;

/**
 * Class AppController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="App")
 *
 */
class AppController extends Controller
{
    /**
     * @OA\Get(
     *      path="/app/{user_id}/",
     *      tags={"App"},
     *      description="Main screen",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="ID of user",
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
     *              @OA\Items(
     *                  @OA\Property(
     *                      property="courses",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="not_available",
     *                              type="array",
     *                              @OA\Items(ref="#/components/schemas/Course"),
     *                          ),
     *                          @OA\Property(
     *                              property="available",
     *                              type="array",
     *                              @OA\Items(ref="#/components/schemas/Course"),
     *                          ),
     *                          @OA\Property(
     *                              property="in_progress",
     *                              type="array",
     *                              @OA\Items(ref="#/components/schemas/Course"),
     *                          ),
     *                          @OA\Property(
     *                              property="finished",
     *                              type="array",
     *                              @OA\Items(ref="#/components/schemas/Course"),
     *                          ),
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="lessons",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/Lesson"),
     *                  ),
     *                  @OA\Property(
     *                      property="passed",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/PassedTest"),
     *                  ),
     *              )
     *          )
     *      ),
     * )
     *
     * @param Request $request
     * @param int $user_id
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function index(Request $request, int $user_id)
    {
        $user = User::with(['courses.modules.lessons', 'passedTests.test.lesson'])
            ->findOrFail($user_id);

        // Собираем все пройденные уроки в массив для последующего подсчета
        $passedLessons = [];
        foreach ($user->passedTests as $passedTest) {
            $passedLessons[$passedTest->test->lesson->id] = $passedTest->test->lesson;
        }

        // Проверим, если урок уже есть в пройденных, то обновим ему статус.
        $lessons = $user->lessons();
        foreach ($lessons as &$lesson) {
            $lesson = $lesson->attachStatus($user)->attachPublish($user);
        }

        return [
            'courses' => $user->parsedCourses(),
            'lessons' => $lessons->values(),
            'passed'  => $user->passedTests,
            'passedLessonsCount' => count($passedLessons),
        ];
    }

    /**
     * @OA\Get(
     *      path="/app/{user_id}/lessons/{lesson_id}",
     *      tags={"App"},
     *      description="Lesson screen",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="ID of user",
     *          in="path",
     *          name="user_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="ID of lesson",
     *          in="path",
     *          name="lesson_id",
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
     *              @OA\Items(
     *                  @OA\Property(
     *                      property="lesson",
     *                      ref="#/components/schemas/Lesson",
     *                  ),
     *                  @OA\Property(
     *                      property="tests",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/Test"),
     *                  ),
     *                  @OA\Property(
     *                      property="contents",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/LessonContent"),
     *                  ),
     *              )
     *          )
     *      ),
     * )
     *
     * @param Request $request
     * @param int $user_id
     * @param int $lesson_id
     * @return array
     */
    public function getLesson(Request $request, int $user_id, int $lesson_id)
    {
        $user = User::findOrFail($user_id);

        $lesson = Lesson::with(['contents' => function ($q) use ($user) {
            $q->where('gender_id', $user->gender_id)
                ->orWhere('gender_id', Gender::getAnyGenderId());

        }])->findOrFail($lesson_id);

        return [
            'lesson' => $lesson,
            'tests' => $lesson->tests,
            'contents' => $lesson->renderContents(),
        ];
    }

    /**
     * @OA\Get(
     *      path="/app/{user_id}/tests/{test_id}",
     *      tags={"App"},
     *      description="Test screen",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="ID of user",
     *          in="path",
     *          name="user_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="ID of test",
     *          in="path",
     *          name="test_id",
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
     *              @OA\Items(
     *                  @OA\Property(
     *                      property="test",
     *                      ref="#/components/schemas/Test",
     *                  ),
     *                  @OA\Property(
     *                      property="passed",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/PassedTest"),
     *                  ),
     *              )
     *          )
     *      ),
     * )
     *
     * @param Request $request
     * @param int $user_id
     * @param int $test_id
     * @return array
     */
    public function getTest(Request $request, int $user_id, int $test_id)
    {
        $pasedTest = PassedTest::withDefaultRelations()
            ->where('test_id', $test_id)
            ->where('user_id', $user_id)
            ->get();

        return [
            'test' => Test::withDefaultRelations()->findOrFail($test_id),
            'passed' => $pasedTest,
        ];
    }

    /**
     *  @OA\Get(
     *      path="/app/{user_id}/stories",
     *      tags={"App"},
     *      description="Stories",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="ID of user",
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
     *                  @OA\Items(ref="#/components/schemas/Story"),
     *              )
     *          )
     *      ),
     * )
     *
     * @param Request $request
     * @param int $user_id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getStories(Request $request, int $user_id)
    {
        $user = User::findOrFail($user_id);

        $stories = Story::with('content')->get();


        $stories->each(function ($story) use ($user){
            foreach ($story->content as &$content) {
                $content->viewed = $content->checkViewed($user);
            }
        });

        return $stories;
    }

    /**
     * @param Request $request
     * @param int $user_id
     * @param int $story_contents_id
     * @return mixed
     *
     *  @OA\Post(
     *      path="/app/{user_id}/stories/{story_content_id}",
     *      tags={"App"},
     *      description="Stories",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="ID of user",
     *          in="path",
     *          name="user_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *         )
     *      ),
     *     @OA\Parameter(
     *          description="ID story content",
     *          in="path",
     *          name="story_content_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/StoryContentToUser")
     *      ),
     * )
     */
    public function saveStory(Request $request, int $user_id, int $story_contents_id)
    {
        $user = User::findOrFail($user_id);
        $storyContent = StoryContent::findOrFail($story_contents_id);

        return StoryContentToUser::firstOrCreate([
            'story_contents_id' => $storyContent->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @param Request $request
     * @param int $story_id
     * @param int $user_id
     * @return Story
     *
     *  @OA\Get(
     *      path="/app/{user_id}/stories/{story_id}",
     *      tags={"App"},
     *      description="Story",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          description="ID of user",
     *          in="path",
     *          name="user_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *         )
     *      ),
     *      @OA\Parameter(
     *          description="Story ID",
     *          in="path",
     *          name="story_id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Story")
     *      ),
     * )
     */
    public function getStory(Request $request, int $user_id, int $story_id)
    {
        $user = User::findOrFail($user_id);

        $story = Story::with('content')->findOrFail($story_id);

        foreach ($story->content as &$content) {
            $content->viewed = $content->checkViewed($user);
        }

        return $story;
    }

    /**
     * @return array
     *
     * @OA\Post(
     *      path="/app/logout",
     *      tags={"App"},
     *      description="Logout",
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *      ),
     * )
     */
    public function logout()
    {
        if (\Auth::check()) {
            \Auth::user()->AauthAcessToken()->delete();
            return [
                'success' => true
            ];
        }
        return [
            'success' => false,
        ];
    }
}
