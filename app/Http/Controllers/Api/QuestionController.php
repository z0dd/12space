<?php

namespace App\Http\Controllers\Api;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class QuestionController
 * @package App\Http\Controllers\Api
 */
class QuestionController extends ApiSpaceController
{
    /**
     * QuestionController constructor.
     * @param Question $model
     */
    public function __construct(Question $model)
    {
        $this->model = $model;
    }
}
