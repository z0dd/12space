<?php

namespace App\Http\Controllers\Api;

use App\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AccountController
 * @package App\Http\Controllers\Api
 */
class AccountController extends ApiSpaceController
{
    /**
     * AccountController constructor.
     * @param Account $model
     */
    public function __construct(Account $model)
    {
        $this->model = $model;
    }
}
