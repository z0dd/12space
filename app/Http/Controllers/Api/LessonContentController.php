<?php

namespace App\Http\Controllers\Api;

use App\LessonContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LessonContentController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Lessons")
 * @OA\Get(
 *      path="/lessons/contents",
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
 *             @OA\Items(ref="#/components/schemas/LessonContent")
 *         ),
 *       ),
 * )
 * @OA\Get(
 *      path="/lessons/contents/{id}",
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
 *          @OA\JsonContent(ref="#/components/schemas/LessonContent"),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class LessonContentController extends ApiSpaceController
{
    /**
     * LessonContentController constructor.
     * @param LessonContent $model
     */
    public function __construct(LessonContent $model)
    {
        $this->model = $model;
    }

    public function get(\Illuminate\Http\Request $request, int $id)
    {
        return $this->model->withDefaultRelations()->findOrFail($id)->render();
    }
}
