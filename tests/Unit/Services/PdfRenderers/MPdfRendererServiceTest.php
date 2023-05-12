<?php

namespace Tests\Unit\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Services\PdfRenderers\MpdfRendererService;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use RuntimeException;
use Tests\TestCase;

class MPdfRendererServiceTest extends TestCase
{
    private MpdfRendererService $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new MpdfRendererService();
    }

    public function testRenderOk()
    {
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
        $mpdf = $this->createMock(Mpdf::class);
        $mpdf->expects($this->once())
            ->method('WriteHTML')
            ->willReturnSelf();
        $mpdf->expects($this->once())
            ->method('Output')
            ->willThrowException(new RuntimeException('Error'));

        $this->app->offsetSet('mpdf-testing', $mpdf);

        $renderResult = $this->renderer->render(
            DocumentTemplate::factory()->create(),
            [],
            [
                'use-test-instance' => true,
            ]
        );

        $this->assertFalse($renderResult->isOk());
        $this->assertSame(
            PdfRenderErrorCode::UNEXPECTED_ERROR,
            $renderResult->getErrorResult()->errorCode
        );
    }

    public function testRenderErrorWhileStoringTheFile()
    {
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
