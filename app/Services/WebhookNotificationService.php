<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookNotificationService
{
    public function notify(
        string $webhookUrl,
        array $body = []
    ): void {
        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->post($webhookUrl, $body);

        Log::info('Request webhook', [
            'result' => $response->body(),
            'statusCode' => $response->status(),
        ]);
    }
}
