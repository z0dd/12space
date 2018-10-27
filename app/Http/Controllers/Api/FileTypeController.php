<?php

namespace App\Http\Controllers\Api;

use App\FileTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class FileTypeController
 * @package App\Http\Controllers\Api
 */
class FileTypeController extends ApiSpaceController
{
    /**
     * FileTypeController constructor.
     * @param FileTypes $model
     */
    public function __construct(FileTypes $model)
    {
        $this->model = $model;
    }
}
