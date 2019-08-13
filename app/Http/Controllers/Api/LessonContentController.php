<?php

namespace App\Http\Controllers\Api;

use App\LessonContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @param int $content_id
     * @param int $content_index
     *
     * @return mixed
     *
     *
     * @OA\Tag(name="Lessons")
     * @OA\Get(
     *      path="/content/{id}/{index}",
     *      tags={"Lessons"},
     *      description="Return content",
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
     *      @OA\Parameter(
     *          description="index",
     *          in="path",
     *          name="index",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\Schema(
     *              type="file"
     *         )
     *       ),
     *      @OA\Response(response=404, description="Not found"),
     * )
     */
    public function getMaskedContent(int $content_id, int $content_index) {

        $file = LessonContent::where('file_type_id',2)->findOrFail($content_id);

        $files = json_decode($file->file,1);

        if (is_null($files) || empty($files[$content_index]['download_link'])) {
            abort(404);
        }

        return Storage::disk('public')->download($files[$content_index]['download_link']);
    }
}
