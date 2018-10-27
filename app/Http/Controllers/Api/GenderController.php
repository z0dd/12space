<?php

namespace App\Http\Controllers\Api;

use App\Gender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class GenderController
 * @package App\Http\Controllers\Api
 */
class GenderController extends ApiSpaceController
{
    /**
     * GenderController constructor.
     * @param Gender $model
     */
    public function __construct(Gender $model)
    {
        $this->model = $model;
    }
}
