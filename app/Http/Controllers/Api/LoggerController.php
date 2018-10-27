<?php

namespace App\Http\Controllers\Api;

use App\Logger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class LoggerController
 * @package App\Http\Controllers\Api
 */
class LoggerController extends ApiSpaceController
{
    /**
     * LoggerController constructor.
     * @param Logger $model
     */
    public function __construct(Logger $model)
    {
        $this->model = $model;
    }
}
