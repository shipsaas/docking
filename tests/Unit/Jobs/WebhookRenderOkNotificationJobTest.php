<?php

namespace Tests\Unit\Jobs;

use App\Http\Resources\DocumentFileResource;
use App\Jobs\WebhookRenderOkNotificationJob;
use App\Models\DocumentFile;
use App\Services\WebhookNotificationService;
use Tests\TestCase;

class WebhookRenderOkNotificationJobTest extends TestCase
{
    public function testJobSendsARequestToUserWebhook()
    {
        $job = new WebhookRenderOkNotificationJob(
            'https://sethphat.dev/ping',
            $file = DocumentFile::factory()->create()
        );

        $service = $this->createMock(WebhookNotificationService::class);
        $service->expects($this->once())
            ->method('notify')
            ->with('https://sethphat.dev/ping', [
                'file' => DocumentFileResource::make($file)->toArray(app('request')),
            ]);

        $job->handle($service);
    }
}
