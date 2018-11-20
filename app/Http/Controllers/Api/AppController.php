<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lesson;
use App\PassedTest;
use App\Test;
use App\User;
use Illuminate\Http\Request;

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
        $user = User::with(['courses.modules', 'passedTests'])
               ->findOrFail($user_id);

        return [
            'courses' => $user->parsedCourses(),
            'lessons' => $user->lessons(),
            'passed'  => $user->passedTests,
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
        $lesson = Lesson::findOrFail($lesson_id);
        return [
            'lesson' => $lesson,
            'tests' => $lesson->tests,
            'contents' => $lesson->contents,
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
}
