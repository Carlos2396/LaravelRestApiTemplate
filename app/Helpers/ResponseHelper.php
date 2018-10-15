<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Response;

class ResponseHelper 
{
    public static function validationErrorResponse($errors)  {
        $body = [
            "message" => "Failed data validation",
            "errors" => $errors
        ];

        return response()->json($body, 400);
    }
}