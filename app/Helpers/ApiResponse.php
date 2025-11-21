<?php

namespace App\Helpers;

class ApiResponse
{
    public static function sendResponse($statusCode, $message, $data = null, $errors = [])
    {
        $status = $statusCode >= 200 && $statusCode < 300 ? 'true' : 'false';

        if (!empty($errors)) {
            $message = $errors;
            $errors  = null;
        }

        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }
}
