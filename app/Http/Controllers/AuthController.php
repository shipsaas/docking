<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function access(): JsonResponse
    {
        return new JsonResponse([
            'ok' => true,
        ]);
    }
}
