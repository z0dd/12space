<?php

namespace App\Http\Controllers\Api;

use App\CourseStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CourseStatusController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Courses")
 * @OA\Get(
 *      path="/courses/statuses",
 *      tags={"Courses"},
 *      description="Get list of all models without any definitions",
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/CourseStatus")
 *         ),
 *      ),
 * )
 * @OA\Get(
 *      path="/courses/statuses/{id}",
 *      tags={"Courses"},
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
 *          @OA\JsonContent(ref="#/components/schemas/Course"),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class CourseStatusController extends ApiSpaceController
{
    /**
     * CourseStatusController constructor.
     * @param CourseStatus $model
     */
    public function __construct(CourseStatus $model)
    {
        $this->model = $model;
    }
}
