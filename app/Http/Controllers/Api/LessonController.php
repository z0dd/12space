<?php

namespace App\Http\Controllers\Api;

use App\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LessonController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Lessons")
 * @OA\Get(
 *      path="/lessons",
 *      tags={"Lessons"},
 *      description="Get list of all models without any definitions",
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Lesson")
 *         ),
 *       ),
 * )
 * @OA\Get(
 *      path="/lessons/{id}",
 *      tags={"Lessons"},
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
 *          @OA\JsonContent(ref="#/components/schemas/Lesson"),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class LessonController extends ApiSpaceController
{
    /**
     * LessonController constructor.
     * @param Lesson $model
     */
    public function __construct(Lesson $model)
    {
        $this->model = $model;
    }
}
