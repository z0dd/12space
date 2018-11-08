<?php

namespace App\Http\Controllers\Api;

use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AccountController
 * @package App\Http\Controllers\Api
 *
 * @OA\Tag(name="Accounts")
 * @OA\Get(
 *      path="/accounts",
 *      tags={"Accounts"},
 *      description="Get list of all models without any definitions",
 *      @OA\Response(
 *          response=200,
 *          description="successful operation",
 *          @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Account")
 *         ),
 *       ),
 * )
 * @OA\Get(
 *      path="/accounts/{id}",
 *      tags={"Accounts"},
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
 *          @OA\JsonContent(ref="#/components/schemas/Account"),
 *       ),
 *      @OA\Response(response=404, description="Not found"),
 * )
 */
class AccountController extends ApiSpaceController
{
    /**
     * AccountController constructor.
     * @param Account $model
     */
    public function __construct(Account $model)
    {
        $this->model = $model;
    }
}
