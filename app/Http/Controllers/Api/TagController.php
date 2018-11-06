<?php

namespace App\Http\Controllers\Api;

use App\Tag;

/**
 * Class TagController
 * @package App\Http\Controllers\Api
 */
class TagController extends ApiSpaceController
{
    /**
     * TagController constructor.
     * @param Tag $model
     */
    public function __construct(Tag $model)
    {
        $this->model = $model;
    }
}
