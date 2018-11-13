<?php

namespace App\Http\Controllers\Api;

use App\Logger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LoggerController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="History")
 * @OA\Get(
 *      path="/history",
 *      tags={"History"},
 *      description="Get list of all models without any definitions",
 *      security={
 *          {"passport": {}},
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/History")
 *          )
 *       ),
 * )
 * @OA\Get(
 *      path="/history/{id}",
 *      tags={"History"},
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
 *          @OA\JsonContent(ref="#/components/schemas/History")
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class LoggerController extends ApiSpaceController
{
    /**
     * LoggerController constructor.
     * @param Logger $model
     */
    public function __construct(Logger $model)
    {
        $this->model = $model;
    }
}
