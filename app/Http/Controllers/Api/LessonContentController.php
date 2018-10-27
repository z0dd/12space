<?php

namespace App\Http\Controllers\Api;

use App\LessonContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LessonContentController
 * @package App\Http\Controllers\Api
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
}
