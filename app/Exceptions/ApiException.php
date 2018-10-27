<?php

namespace App\Exceptions;

use Exception;
use Log;

class ApiException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param $request
     * @param Exception $exception
     * @return mixed
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
