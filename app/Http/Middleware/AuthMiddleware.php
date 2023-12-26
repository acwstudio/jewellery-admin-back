<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return Response|RedirectResponse
     */
    public function handle($request, Closure $next)
    {
//        $accessToken = $request->bearerToken();
//        if (!$accessToken or !JWTCheckHelper::checkAccessToken($accessToken)) {
//
//            /*                $message = [
//                                "code" => 1,
//                                "message" => __("message.access_token_invalid"),
//                            ];*/
//            return response("", 401)->header('Content-Type', 'text/plain');
//        }

        return $next($request);
    }
}
