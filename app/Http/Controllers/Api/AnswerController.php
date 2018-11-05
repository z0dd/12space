<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Exceptions\ApiException;
use App\PassedTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AnswerController
 * @package App\Http\Controllers\Api
 */
class AnswerController extends ApiSpaceController
{
    /**
     * AnswerController constructor.
     * @param Answer $model
     */
    public function __construct(Answer $model)
    {
        $this->model = $model;
    }
}
