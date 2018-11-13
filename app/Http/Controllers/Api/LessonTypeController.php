<?php

namespace App\Http\Controllers\Api;

use App\LessonType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LessonTypeController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Lessons")
 * @OA\Get(
 *      path="/lessons/types",
 *      tags={"Lessons"},
 *      description="Get list of all models without any definitions",
 *      security={
 *          {"passport": {}},
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/LessonType")
 *         )
 *       ),
 * )
 * @OA\Get(
 *      path="/lessons/types/{id}",
 *      tags={"Lessons"},
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
 *          @OA\JsonContent(ref="#/components/schemas/LessonType"),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class LessonTypeController extends Controller
{
    /**
     * LessonTypeController constructor.
     * @param LessonType $model
     */
    public function __construct(LessonType $model)
    {
        $this->model = $model;
    }
}
