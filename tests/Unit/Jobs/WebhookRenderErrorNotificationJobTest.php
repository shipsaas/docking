<?php

namespace Tests\Unit\Jobs;

use App\Jobs\WebhookRenderErrorNotificationJob;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Services\WebhookNotificationService;
use Tests\TestCase;

class WebhookRenderErrorNotificationJobTest extends TestCase
{
    public function testJobSendsARequestToUserWebhook()
    {
        $job = new WebhookRenderErrorNotificationJob(
            'https://sethphat.dev/ping',
            PdfRenderErrorCode::TIMEOUT
        );

        $service = $this->createMock(WebhookNotificationService::class);
        $service->expects($this->once())
            ->method('notify')
            ->with('https://sethphat.dev/ping', [
                'outcome' => PdfRenderErrorCode::TIMEOUT->value
            ]);

        $job->handle($service);
    }
}
