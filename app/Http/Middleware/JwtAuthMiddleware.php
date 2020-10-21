<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtAuthMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            if (!$token = $this->auth->setRequest($request)->getToken()) {
                return errorResponse('TOKEN_NOT_PROVIDED', 'Authenticated token is not provided.', STATUS_CODE_UNAUTHORIZED);
            }

            if (!$user = JWTAuth::authenticate($token)) {
                return errorResponse('USER_NOT_FOUND', 'User detail is not found.', STATUS_CODE_NOT_FOUND);
            }

        } catch (\Exception $exception) {
            if ($exception instanceof UnauthorizedHttpException) {
                return errorResponse('TOKEN_NOT_PROVIDED', 'Authenticated token is not provided.', STATUS_CODE_UNAUTHORIZED);
            }

            if ($exception instanceof TokenExpiredException) {
                return errorResponse('TOKEN_EXPIRED', 'Authenticated token is expired.', STATUS_CODE_UNAUTHORIZED);
            }

            if ($exception instanceof JWTException) {
                return errorResponse('TOKEN_INVALID', 'Authenticated token is invalid.', STATUS_CODE_UNAUTHORIZED);
            }

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }

        return $next($request);
    }
}
