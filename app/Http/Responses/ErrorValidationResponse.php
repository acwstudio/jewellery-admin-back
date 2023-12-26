<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ErrorValidationResponse
{
    /**
     * @param string $message
     * @return JsonResponse
     */
    public static function send(string $message): JsonResponse
    {
        return new JsonResponse(['errors' => json_decode($message)],400);
    }
}
