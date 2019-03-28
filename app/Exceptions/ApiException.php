<?php

namespace App\Exceptions;

use Exception;
use Log;

/**
 * Class ApiException
 * @package App\Exceptions
 */
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
     * @return mixed
     */
    public function render($request)
    {
        $error = [
            'status' => 'error',
            'text'  => $this->getMessage()
        ];

        if ($request->wantsJson()) {
            return response()->json($error, 400);
        }

        return parent::render($request);
    }
}
