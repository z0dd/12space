<?php
/**
 * Created by PhpStorm.
 * User: z0dd
 * Date: 28.03.2019
 * Time: 9:05
 */

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\UserHashAuth;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

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
        $isAdmin = Auth::user()->role_id == 1;

        if ($request->route()->getName() !== 'apiResetUserPassword' && $isAdmin == false) {
            if ($this->checkUserHash() === false) {
                throw new ApiException('User multiply devices', 403);
            } else {
                $response->withCookie(
                    cookie()->forever(
                        self::COOKIE_HASH_NAME,
                        Auth::user()->userHash()->first()->hash,
                        '/',
                        env('APP_MAIN_DOMAIN','12space.ru')
                    )
                );
            }
        }

        // Если запрос содержит ID пользователя
        if ($request->route('user_id') !== null && $isAdmin == false) {

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

    /**
     * название куки для хеша
     */
    const COOKIE_HASH_NAME = 'space_hash';

    /**
     * @return bool
     */
    private function checkUserHash() :bool
    {
        if (Auth::guest()) {
            return false;
        }

        $userHash = Auth::user()->userHash()->first();

        // Если у пользователя нет хеша, создадим ему его и поставим куку с ним.
        if (is_null($userHash)) {
            $userHash = new UserHashAuth([
                'user_id' => Auth::user()->id,
                'hash' => uniqid(),
            ]);
            $userHash->save();

            return true;
        }

        $cookieHash = Cookie::get(self::COOKIE_HASH_NAME);
        // Если хеш есть, но нет куки, блочим вход.
        if (false == $cookieHash) {
            return false;
        }

        return $cookieHash === $userHash->hash;
    }
}
