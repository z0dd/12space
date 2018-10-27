<?php

namespace App\Http\Controllers\Api;

use App\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LessonController
 * @package App\Http\Controllers\Api
 */
class LessonController extends ApiSpaceController
{
    /**
     * LessonController constructor.
     * @param Lesson $model
     */
    public function __construct(Lesson $model)
    {
        $this->model = $model;
    }
}
