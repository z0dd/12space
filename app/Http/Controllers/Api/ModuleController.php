<?php

namespace App\Http\Controllers\Api;

use App\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class ModuleController
 * @package App\Http\Controllers\Api
 */
class ModuleController extends ApiSpaceController
{
    /**
     * ModuleController constructor.
     * @param Module $model
     */
    public function __construct(Module $model)
    {
        $this->model = $model;
    }
}
