<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return errorResponse('UNAUTHORIZED_ERROR', 'User unauthorized.', STATUS_CODE_UNAUTHORIZED);
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => auth()->user(),
            ], STATUS_CODE_SUCCESS);
        } catch (\Throwable $exception) {
            logError($exception, 'Error while logging in', 'AuthController@login');

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Throwable $exception) {
            logError($exception, 'Error while logout', 'AuthController@logout');

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }
    }
}
