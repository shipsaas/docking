<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WebhookNotificationService
{
    public function notify(
        string $webhookUrl,
        array $body = []
    ): void {
        Http::post($webhookUrl, $body);
    }
}
