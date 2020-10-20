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
