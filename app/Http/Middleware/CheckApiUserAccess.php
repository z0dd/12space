<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 28.03.2019
 * Time: 9:05
 */

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckApiUserAccess
 * @package App\Http\Middleware
 */
class CheckApiUserAccess
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws ApiException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Если пользователь я, то отдадим мне что я хочу. :D
        if (false == Auth::guest() && Auth::user()->id == 1) { return $response;}

        // Если запрос содержит ID пользователя
        if ($request->route('user_id') !== null) {

            // Закрываем доступ ко всем пользователям кроме своего
            if (
                Auth::guest()
                || Auth::user()->id != $request->route('user_id')
            ) {
                throw new ApiException('Access denied', 403);
            }
        }

        return $response;
    }
}
