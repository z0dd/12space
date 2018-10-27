<?php

namespace App\Http\Controllers\Api;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CourseController
 * @package App\Http\Controllers\Api
 */
class CourseController extends ApiSpaceController
{
    /**
     * CourseController constructor.
     * @param Course $model
     */
    public function __construct(Course $model)
    {
        $this->model = $model;
    }
}
