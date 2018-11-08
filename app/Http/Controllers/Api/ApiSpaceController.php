<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 27.10.2018
 * Time: 9:36
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;

/**
 * @OA\OpenApi(
 *     @OA\Server(
 *         url="L5_SWAGGER_CONST_HOST",
 *         description="API server"
 *     ),
 *     @OA\Info(
 *          version="1.0.0",
 *          title="12space",
 *          description="Swagger описание API для 12space",
 *          @OA\Contact(
 *              email="z0dd@mail.ru"
 *          )
 *     ),
 * )
 *
 * Class ApiSpaceController
 * @package App\Http\Controllers\Api
 */
abstract class ApiSpaceController extends Controller
{
    /**
     * @var
     */
    protected $model;

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function show(\Illuminate\Http\Request $request)
    {
        return $this->model->all();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return mixed
     */
    public function get(\Illuminate\Http\Request $request, int $id)
    {
        return $this->model->withDefaultRelations()->findOrFail($id);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return mixed
     * @throws ApiException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function set(\Illuminate\Http\Request $request, int $id)
    {
        $validated = $this->validate($request, $this->model->rules());

        $model = $this->model->findOrFail($id);

        $model->fill($validated);

        if (false == $model->save()) {
            throw new ApiException('Not saved', 500);
        }

        return $model->fresh();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws ApiException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(\Illuminate\Http\Request $request)
    {
        $validated = $this->validate($request, $this->model->rules());

        $model = new $this->model;

        $model->fill($validated);

        if (false == $model->save()) {
            throw new ApiException('Not saved', 500);
        }

        return $model->fresh();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function delete(\Illuminate\Http\Request $request, $id)
    {
        $model = $this->model->findOrFail($id);

        if (false == $model->delete()) {
            throw new ApiException('Not deleted', 500);
        }

        return response([true]);
    }
}
