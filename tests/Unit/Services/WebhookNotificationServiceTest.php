<?php

namespace Tests\Unit\Services;

use App\Services\WebhookNotificationService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WebhookNotificationServiceTest extends TestCase
{
    public function testSilentlyTriggerTheWebhookToNotifySubscribedClient()
    {
        Http::expects('post')
            ->with('https://sethphat.dev', [
                'ok' => true,
            ])
            ->andReturn($this->createMock(Response::class));

        $service = new WebhookNotificationService();

        $service->notify('https://sethphat.dev', [
            'ok' => true,
        ]);
    }
}
