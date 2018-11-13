<?php

namespace App\Http\Controllers\Api;

use App\Test;

/**
 * Class TestController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Tests")
 * @OA\Get(
 *      path="/tests",
 *      tags={"Tests"},
 *      description="Get list of all models without any definitions",
 *      security={
 *          {"passport": {}},
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Test")
 *         ),
 *       ),
 * )
 * @OA\Get(
 *      path="/tests/{id}",
 *      tags={"Tests"},
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
 *          @OA\JsonContent(ref="#/components/schemas/Test"),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class TestController extends ApiSpaceController
{
    /**
     * TestController constructor.
     * @param Test $model
     */
    public function __construct(Test $model)
    {
        $this->model = $model;
    }
}
