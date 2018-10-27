<?php

namespace App\Http\Controllers\Api;

use App\LessonType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LessonTypeController
 * @package App\Http\Controllers\Api
 */
class LessonTypeController extends Controller
{
    /**
     * LessonTypeController constructor.
     * @param LessonType $model
     */
    public function __construct(LessonType $model)
    {
        $this->model = $model;
    }
}
