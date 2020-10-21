<?php

/**
 * Formatted logs for easier reference
 *
 * @param $error
 * @param $message
 * @param $location
 * @param array $params
 */
function logError($error, $message, $location, $params = [])
{
    \Log::error([
        $message => [
            'location' => $location,
            'message' => $error->getMessage(),
            'params' => $params,
            'trace'=> $error->getTraceAsString(),
        ],
    ]);
}

/**
 * Formatted error response
 *
 * @param $code
 * @param $message
 * @param $statusCode
 * @return \Illuminate\Http\JsonResponse
 */
function errorResponse($code, $message, $statusCode)
{
    return response()->json(getCommonErrorResponse($code, $message), $statusCode);
}

/**
 * @param $code
 * @param $message
 * @return array
 */
function getCommonErrorResponse($code, $message)
{
    return ['common_error' => [['code' => $code, 'message' => $message]]];
}