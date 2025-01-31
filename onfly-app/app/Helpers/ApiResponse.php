<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message, $data = [])
    {
        return response()->json([
            'message' => $message,
            'error' => '',
            'data' => $data
        ], 200);
    }

    public static function created($message, $data = [])
    {
        return response()->json([
            'message' => $message,
            'error' => '',
            'data' => $data
        ], 201);
    }

    public static function error($message, $status = 400)
    {
        return response()->json([
            'message' => '',
            'error' => $message,
            'data' => []
        ], $status);
    }

    public static function validationError($errors)
    {
        return response()->json([
            'message' => '',
            'error' => 'Validation error',
            'data' => $errors
        ], 422);
    }

    public static function unauthorized()
    {
        return response()->json([
            'message' => '',
            'error' => "You don't have permission to perform this action",
            'data' => []
        ], 401);
    }

    public static function notFound($message = 'Resource not found')
    {
        return response()->json([
            'message' => '',
            'error' => $message,
            'data' => []
        ], 404);
    }
}
