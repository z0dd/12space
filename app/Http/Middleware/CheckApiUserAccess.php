<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 28.03.2019
 * Time: 9:05
 */

namespace App\Http\Middleware;

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
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Если запрос содержит ID пользователя
        if ($request->route('user_id') !== null) {
            // Закрываем доступ ко всем пользователям кроме своего
            if (Auth::user()->id != $request->route('user_id')) {
                abort(403);
            }
        }

        return $response;
    }
}
