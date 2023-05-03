<?php

namespace Tests\Unit\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Services\PdfRenderers\WkHtmlToPdfRendererService;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Process\ProcessResult;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WkHtmlToPdfRendererServiceTest extends TestCase
{
    private WkHtmlToPdfRendererService $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new WkHtmlToPdfRendererService();
    }

    public function testRenderOk()
    {
        Process::expects('command')
            ->once()
            ->andReturnSelf();
        Process::expects('run')
            ->once()
            ->andReturn($commandResult = $this->createMock(ProcessResult::class));

        $commandResult->method('successful')->willReturn(true);

        $template = DocumentTemplate::factory()->create();
        $renderResult = $this->renderer->render($template);

        $this->assertTrue($renderResult->isOk());
        $this->assertNotNull($renderResult->getOkResult()->file);

        $this->assertDatabaseHas('document_files', [
            'document_template_uuid' => $template->uuid,
        ]);
    }

    public function testRenderErrorWhileRenderingThePdf()
    {
        Process::expects('command')
            ->once()
            ->andReturnSelf();
        Process::expects('run')
            ->once()
            ->andReturn($commandResult = $this->createMock(ProcessResult::class));

        $commandResult->method('successful')->willReturn(false);

        $renderResult = $this->renderer->render(
            DocumentTemplate::factory()->create()
        );

        $this->assertFalse($renderResult->isOk());
        $this->assertSame(
            PdfRenderErrorCode::UNEXPECTED_ERROR,
            $renderResult->getErrorResult()->errorCode
        );
    }

    public function testRenderErrorWhileRenderingThePdfTimeout()
    {
        Process::expects('command')
            ->once()
            ->andReturnSelf();
        Process::expects('run')
            ->once()
            ->andThrow($this->createMock(ProcessTimedOutException::class));

        $renderResult = $this->renderer->render(
            DocumentTemplate::factory()->create()
        );

        $this->assertFalse($renderResult->isOk());
        $this->assertSame(
            PdfRenderErrorCode::TIMEOUT,
            $renderResult->getErrorResult()->errorCode
        );
    }

    public function testRenderErrorWhileStoringTheFile()
    {
        Process::expects('command')
            ->once()
            ->andReturnSelf();
        Process::expects('run')
            ->once()
            ->andReturn($commandResult = $this->createMock(ProcessResult::class));

        $commandResult->method('successful')->willReturn(true);

        Storage::expects('putFile')
            ->once()
            ->andReturn(false);

        $template = DocumentTemplate::factory()->create();
        $renderResult = $this->renderer->render($template);

        $this->assertFalse($renderResult->isOk());
        $this->assertSame(
            PdfRenderErrorCode::STORE_FILE_FAILED,
            $renderResult->getErrorResult()->errorCode
        );
    }
}
