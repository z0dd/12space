<?php

namespace App\Http\Controllers\Api;

use App\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class ModuleController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Modules")
 * @OA\Get(
 *      path="/modules",
 *      tags={"Modules"},
 *      description="Get list of all models without any definitions",
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Module")
 *          )
 *       ),
 * )
 * @OA\Get(
 *      path="/modules/{id}",
 *      tags={"Modules"},
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
 *          @OA\JsonContent(ref="#/components/schemas/Module")
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class ModuleController extends ApiSpaceController
{
    /**
     * ModuleController constructor.
     * @param Module $model
     */
    public function __construct(Module $model)
    {
        $this->model = $model;
    }
}
