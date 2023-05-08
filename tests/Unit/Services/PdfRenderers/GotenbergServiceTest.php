<?php

namespace Tests\Unit\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Services\PdfRenderers\GotenbergRendererService;
use Illuminate\Http\Client\Response;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Process\ProcessResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GotenbergServiceTest extends TestCase
{
    private GotenbergRendererService $renderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->renderer = new GotenbergRendererService('http://internal-gotenberg.shipsaas.tech');
    }

    public function testRenderOkUsesDefaultEngine()
    {
        Http::expects('asMultipart')
            ->once()
            ->andReturnSelf();
        Http::expects('attach')
            ->once()
            ->andReturnSelf();
        Http::expects('post')
            ->once()
            ->with(
                'http://internal-gotenberg.shipsaas.tech/forms/chromium/convert/html',
                []
            )
            ->andReturn($httpResponse = $this->createMock(Response::class));

        $httpResponse->method('successful')->willReturn(true);
        $httpResponse->method('header')->willReturn('attachment');
        $httpResponse->method('body')->willReturn('Seth Tran');

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
        Http::expects('asMultipart')
            ->once()
            ->andReturnSelf();
        Http::expects('attach')
            ->once()
            ->andReturnSelf();
        Http::expects('post')
            ->once()
            ->with(
                'http://internal-gotenberg.shipsaas.tech/forms/chromium/convert/html',
                []
            )
            ->andReturn($httpResponse = $this->createMock(Response::class));

        $httpResponse->method('successful')->willReturn(false);

        $renderResult = $this->renderer->render(
            DocumentTemplate::factory()->create()
        );

        $this->assertFalse($renderResult->isOk());
        $this->assertSame(
            PdfRenderErrorCode::UNEXPECTED_ERROR,
            $renderResult->getErrorResult()->errorCode
        );
    }

    public function testRenderErrorWhileRenderingThePdfMissingHeader()
    {
        Http::expects('asMultipart')
            ->once()
            ->andReturnSelf();
        Http::expects('attach')
            ->once()
            ->andReturnSelf();
        Http::expects('post')
            ->once()
            ->with(
                'http://internal-gotenberg.shipsaas.tech/forms/chromium/convert/html',
                []
            )
            ->andReturn($httpResponse = $this->createMock(Response::class));

        $httpResponse->method('successful')->willReturn(true);
        $httpResponse->method('header')->willReturn(null);

        $renderResult = $this->renderer->render(
            DocumentTemplate::factory()->create()
        );

        $this->assertFalse($renderResult->isOk());
        $this->assertSame(
            PdfRenderErrorCode::UNEXPECTED_ERROR,
            $renderResult->getErrorResult()->errorCode
        );
    }

    public function testRenderErrorWhileStoringTheFile()
    {
        Http::expects('asMultipart')
            ->once()
            ->andReturnSelf();
        Http::expects('attach')
            ->once()
            ->andReturnSelf();
        Http::expects('post')
            ->once()
            ->with(
                'http://internal-gotenberg.shipsaas.tech/forms/chromium/convert/html',
                []
            )
            ->andReturn($httpResponse = $this->createMock(Response::class));

        $httpResponse->method('successful')->willReturn(true);
        $httpResponse->method('header')->willReturn('attachment');
        $httpResponse->method('body')->willReturn('Seth Tran');

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
