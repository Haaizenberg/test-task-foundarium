<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SuccessController extends Controller
{
    /**
     * Возвращает всегда успех
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public static function success(): JsonResponse
    {
        return response()->json([
            'Response' => 'success',
        ]);
    }
}
