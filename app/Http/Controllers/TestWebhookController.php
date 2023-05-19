<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestWebhookController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        DB::table('webhook_records')->insert([
            'url' => $request->input('file.url'),
        ]);

        return new JsonResponse('OK');
    }
}
