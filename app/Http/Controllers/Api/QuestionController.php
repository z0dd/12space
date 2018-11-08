<?php

namespace App\Http\Controllers\Api;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class QuestionController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Questions")
 * @OA\Get(
 *      path="/questions",
 *      tags={"Questions"},
 *      description="Get list of all models without any definitions",
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Question")
 *          )
 *       ),
 * )
 * @OA\Get(
 *      path="/questions/{id}",
 *      tags={"Questions"},
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
 *          @OA\JsonContent(ref="#/components/schemas/Question")
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class QuestionController extends ApiSpaceController
{
    /**
     * QuestionController constructor.
     * @param Question $model
     */
    public function __construct(Question $model)
    {
        $this->model = $model;
    }
}
