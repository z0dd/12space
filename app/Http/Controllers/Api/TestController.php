<?php

namespace App\Http\Controllers\Api;

use App\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class TestController
 * @package App\Http\Controllers\Api
 */
class TestController extends ApiSpaceController
{
    /**
     * TestController constructor.
     * @param Test $model
     */
    public function __construct(Test $model)
    {
        $this->model = $model;
    }
}
