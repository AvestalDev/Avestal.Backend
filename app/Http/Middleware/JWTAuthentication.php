<?php

namespace App\Http\Middleware;

use App\Models\AccessIP;
use App\Models\User;
use App\Models\Whitelist;
use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthentication extends BaseMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        try {
            $whitelist = Whitelist::where('access_token', JWTAuth::getToken())->first();

            if (is_null($whitelist)) {
                return response()->json([
                    'message' => 'Ключ аунтефикации не найден'
                ], 403);
            }

            $access_ip = AccessIP::where('ip', $whitelist->ip)->first();

            if (is_null($access_ip)) {
                return response()->json([
                    'message' => 'Ваш IP-адрес заблокирован'
                ], 403);
            } else {
                JWTAuth::parseToken()->authenticate();
            }
        } catch (Exception $e) {
            if ($e instanceof TokenExpiredException) {

                $whitelist = Whitelist::where('access_token', JWTAuth::getToken())->first();

                if (is_null($whitelist)) {
                    return response()->json([
                        'message' => 'Ключ аунтефикации не найден'
                    ], 403);
                }

                $token = JWTAuth::parseToken()->refresh();

                $whitelist->access_token = $token;
                $whitelist->save();

                return response()->json([
                    'access_token' => $token
                ], 401);
            } else if ($e instanceof TokenInvalidException) {
                return response()->json([
                    'message' => 'Ключ аунтефикации недействительный'
                ], 401);
            } else {
                return response()->json([
                    'message' => 'Ключ аунтефикации не найден'
                ], 403);
            }
        }

        return $next($request);
    }
}
