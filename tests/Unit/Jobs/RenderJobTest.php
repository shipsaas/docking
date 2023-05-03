<?php

namespace Tests\Unit\Jobs;

use App\Events\PdfRendered;
use App\Jobs\RenderJob;
use App\Jobs\WebhookRenderErrorNotificationJob;
use App\Jobs\WebhookRenderOkNotificationJob;
use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RenderJobTest extends TestCase
{
    public function testJobRendersTheFileOk()
    {
        Queue::fake([
            WebhookRenderOkNotificationJob::class,
        ]);
        Event::fake([
            PdfRendered::class,
        ]);

        $pdfRenderManager = $this->createMock(PdfRenderManager::class);
        $pdfRenderManager->expects($this->once())
            ->method('render')
            ->willReturn(PdfRenderResult::ok(new PdfRenderOkOutcome(
                $file = DocumentFile::factory()->create()
            )));

        $job = new RenderJob($file->documentTemplate, [], [
            'webhook_url' => 'https://sethphat.dev',
        ]);

        $job->handle($pdfRenderManager);

        Queue::assertPushed(
            WebhookRenderOkNotificationJob::class,
            fn (WebhookRenderOkNotificationJob $job)
                => $job->webhookUrl === 'https://sethphat.dev' && $job->documentFile->is($file)
        );
        Event::assertDispatched(PdfRendered::class);
    }

    public function testJobRendersTheFileFailed()
    {
        Queue::fake([
            WebhookRenderErrorNotificationJob::class,
        ]);

        $pdfRenderManager = $this->createMock(PdfRenderManager::class);
        $pdfRenderManager->expects($this->once())
            ->method('render')
            ->willReturn(PdfRenderResult::error(new PdfRenderErrorOutcome(
                PdfRenderErrorCode::UNEXPECTED_ERROR
            )));

        $job = new RenderJob(DocumentTemplate::factory()->create(), [], [
            'webhook_url' => 'https://sethphat.dev',
        ]);

        $job->handle($pdfRenderManager);

        Queue::assertPushed(
            WebhookRenderErrorNotificationJob::class,
            fn (WebhookRenderErrorNotificationJob $job)
            => $job->webhookUrl === 'https://sethphat.dev'
                && $job->errorCode === PdfRenderErrorCode::UNEXPECTED_ERROR
        );
    }
}
