<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    protected function success($data, int $status = 200): JsonResponse
    {
        // Standard envelope
        return response()->json(['data' => $data], $status);
    }
}


