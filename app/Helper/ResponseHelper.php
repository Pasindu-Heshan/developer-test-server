<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public function response($status,$message,$data,$httpCode): JsonResponse
    {
        return response()->json ([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ],$httpCode);
    }
}
