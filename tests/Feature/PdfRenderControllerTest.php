<?php

namespace Tests\Feature;

use App\Jobs\RenderJob;
use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderManager;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PdfRenderControllerTest extends TestCase
{
    public function testRenderSyncOk()
    {
        /** @var DocumentFile $file */
        $file = DocumentFile::factory()->create();
        $template = $file->documentTemplate;

        $manager = $this->createMock(PdfRenderManager::class);
        $manager->expects($this->once())
            ->method('render')
            ->willReturn(PdfRenderResult::ok(new PdfRenderOkOutcome(
                $file
            )));
        $this->app->offsetSet(PdfRenderManager::class, $manager);

        $this->json('POST', "api/v1/document-templates/{$template->uuid}/pdfs", [
            'variables' => [
                'seth' => 'phat',
            ],
            'metadata' => [
                'driver' => 'gotenberg',
            ],
        ])
            ->assertOk()
            ->assertJsonFragment([
                'outcome' => 'SUCCESS',
                'document_file_uuid' => $file->uuid,
                'url' => $file->url,
            ]);
    }

    public function testRenderSyncFailed()
    {
        $template = DocumentTemplate::factory()->create();

        $manager = $this->createMock(PdfRenderManager::class);
        $manager->expects($this->once())
            ->method('render')
            ->willReturn(PdfRenderResult::error(new PdfRenderErrorOutcome(
                PdfRenderErrorCode::TIMEOUT
            )));
        $this->app->offsetSet(PdfRenderManager::class, $manager);

        $this->json('POST', "api/v1/document-templates/{$template->uuid}/pdfs", [
            'variables' => [
                'seth' => 'phat',
            ],
            'metadata' => [
                'driver' => 'gotenberg',
            ],
        ])
            ->assertBadRequest()
            ->assertJsonFragment([
                'outcome' => PdfRenderErrorCode::TIMEOUT->value,
            ]);
    }

    public function testRenderAsyncOk()
    {
        Queue::fake([
            RenderJob::class,
        ]);

        $template = DocumentTemplate::factory()->create();

        $this->json('POST', "api/v1/document-templates/{$template->uuid}/pdfs-async", [
            'webhook_url' => 'https://sethphat.dev',
        ])
            ->assertOk()
            ->assertJsonFragment([
                'outcome' => 'QUEUED',
            ]);

        Queue::assertPushed(
            RenderJob::class,
            fn (RenderJob $job) =>
                $job->template->is($template) &&
                $job->metadata['webhook_url'] === 'https://sethphat.dev'
        );
    }
}
