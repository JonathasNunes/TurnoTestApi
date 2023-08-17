<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class ApiProtectedRoute extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['json' => 'Token is Invalid!']);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(['json' => 'Token is Expired!']);
            } else {
                return response()->json(['json' => 'Authorization Token not found!']);
            }
        }

        return $next($request);
    }
}
