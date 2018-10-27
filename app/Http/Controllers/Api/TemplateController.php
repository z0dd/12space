<?php

namespace App\Http\Controllers\Api;


use App\Template;

/**
 * Class TemplateController
 * @package App\Http\Controllers\Api
 */
class TemplateController extends ApiSpaceController
{
    /**
     * TemplateController constructor.
     * @param Template $model
     */
    public function __construct(Template $model)
    {
        $this->model = $model;
    }
}
