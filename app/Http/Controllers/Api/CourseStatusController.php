<?php

namespace App\Http\Controllers\Api;

use App\CourseStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class CourseStatusController
 * @package App\Http\Controllers\Api
 */
class CourseStatusController extends ApiSpaceController
{
    /**
     * CourseStatusController constructor.
     * @param CourseStatus $model
     */
    public function __construct(CourseStatus $model)
    {
        $this->model = $model;
    }
}
