<?php

namespace App\Http\Controllers\Api;

use App\LoggerType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LoggerTypeController
 * @package App\Http\Controllers\Api
 */
class LoggerTypeController extends ApiSpaceController
{
    /**
     * LoggerTypeController constructor.
     * @param LoggerType $model
     */
    public function __construct(LoggerType $model)
    {
        $this->model = $model;
    }
}
