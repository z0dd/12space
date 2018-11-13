<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Exceptions\ApiException;
use App\PassedTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AnswerController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Answers")
 * @OA\Get(
 *      path="/answers",
 *      tags={"Answers"},
 *      description="Get list of all models without any definitions",
 *      security={
 *          {"passport": {}},
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Answer")
 *         ),
 *       ),
 * )
 * @OA\Get(
 *      path="/answers/{id}",
 *      tags={"Answers"},
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
 *          @OA\JsonContent(ref="#/components/schemas/Answer"),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class AnswerController extends ApiSpaceController
{
    /**
     * AnswerController constructor.
     * @param Answer $model
     */
    public function __construct(Answer $model)
    {
        $this->model = $model;
    }
}
