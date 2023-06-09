<?php

namespace Tests\Integration;

use App\Enums\PdfService;
use App\Enums\TemplatingMode;
use App\Models\DocumentTemplate;
use App\Models\Font;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Tests\TestCase;

class PdfRenderIntegrationTest extends TestCase
{
    protected DocumentTemplate $template;

    protected function setUp(): void
    {
        parent::setUp();

        $this->template = DocumentTemplate::factory()->create([
            'template' => file_get_contents(__DIR__ . '/__fixtures__/invoice.html'),
            'default_variables' => json_decode(
                file_get_contents(__DIR__ . '/__fixtures__/invoice.json'),
                true
            ),
        ]);
    }

    public function testRenderPdfUsingGotenberg()
    {
        $response = $this->json('POST', 'api/v1/document-templates/' . $this->template->uuid . '/pdfs', [
            'variables' => $this->template->default_variables,
            'metadata' => [
                'driver' => PdfService::GOTENBERG->value,
            ],
        ])->assertOk();

        $url = $response->json('url');

        $content = $this->readPdfToString(
            public_path(
                str_replace(
                    'http://127.0.0.1:8000',
                    '',
                    $url
                )
            )
        );

        $this->assertStringContainsString('Monthly Infrastructure Fee', $content);
        $this->assertStringContainsString('Monthly Software Fee', $content);
        $this->assertStringContainsString('seth@shipsaas.tech', $content);
        $this->assertStringContainsString('docking.shipsaas.tech', $content);
    }

    public function testRenderPdfUsingWkHtmlToPdf()
    {
        $response = $this->json('POST', 'api/v1/document-templates/' . $this->template->uuid . '/pdfs', [
            'variables' => $this->template->default_variables,
            'metadata' => [
                'driver' => PdfService::WK_HTML_TO_PDF->value,
            ],
        ])->assertOk();

        $url = $response->json('url');

        $content = str_replace([' ', "\t"], '', ($this->readPdfToString(
            public_path(
                str_replace(
                    'http://127.0.0.1:8000',
                    '',
                    $url
                )
            )
        )));

        $this->assertStringContainsString('MonthlyInfrastructureFee', $content);
        $this->assertStringContainsString('MonthlySoftwareFee', $content);
        $this->assertStringContainsString('seth@shipsaas.tech', $content);
        $this->assertStringContainsString('docking.shipsaas.tech', $content);
    }

    public function testRenderPdfUsingMPDF()
    {
        $response = $this->json('POST', 'api/v1/document-templates/' . $this->template->uuid . '/pdfs', [
            'variables' => $this->template->default_variables,
            'metadata' => [
                'driver' => PdfService::MPDF->value,
            ],
        ])->assertOk();

        $url = $response->json('url');

        $content = str_replace([' ', "\t"], '', ($this->readPdfToString(
            public_path(
                str_replace(
                    'http://127.0.0.1:8000',
                    '',
                    $url
                )
            )
        )));

        $this->assertStringContainsString('MonthlyInfrastructureFee', $content);
        $this->assertStringContainsString('MonthlySoftwareFee', $content);
        $this->assertStringContainsString('seth@shipsaas.tech', $content);
        $this->assertStringContainsString('docking.shipsaas.tech', $content);
    }

    public function testRenderPdfUsingGotenbergMarkdownTemplate()
    {
        $markdownTemplate = DocumentTemplate::factory()->create([
            'template' => file_get_contents(__DIR__ . '/__fixtures__/email.md'),
            'default_variables' => [
                'name' => 'Markdown Hehe',
            ],
        ]);

        $response = $this->json('POST', 'api/v1/document-templates/' . $markdownTemplate->uuid . '/pdfs', [
            'variables' => $markdownTemplate->default_variables,
            'metadata' => [
                'driver' => PdfService::GOTENBERG->value,
                'templating' => TemplatingMode::MARKDOWN->value,
            ],
        ])->assertOk();

        $url = $response->json('url');

        $content = $this->readPdfToString(
            public_path(
                str_replace(
                    'http://127.0.0.1:8000',
                    '',
                    $url
                )
            )
        );

        $this->assertStringContainsString('Markdown Hehe', $content);
    }

    public function testRenderPdfUsingMPdfWithCustomFont()
    {
        Font::factory()->create([
            'key' => 'freeserif',
            'path' => UploadedFile::createFromBase(new File(
                'freeserif.ttf',
                fopen(__DIR__ . '/../__fixtures__/freeserif.ttf', 'r')
            )),
        ]);

        $response = $this->json('POST', 'api/v1/document-templates/' . $this->template->uuid . '/pdfs', [
            'variables' => $this->template->default_variables,
            'metadata' => [
                'driver' => PdfService::MPDF->value,
                'custom-fonts' => [
                    'freeserif',
                ],
            ],
        ])->assertOk();

        $url = $response->json('url');

        $content = str_replace([' ', "\t"], '', ($this->readPdfToString(
            public_path(
                str_replace(
                    'http://127.0.0.1:8000',
                    '',
                    $url
                )
            )
        )));

        $this->assertStringContainsString('MonthlyInfrastructureFee', $content);
        $this->assertStringContainsString('MonthlySoftwareFee', $content);
        $this->assertStringContainsString('seth@shipsaas.tech', $content);
        $this->assertStringContainsString('docking.shipsaas.tech', $content);
    }

    private function readPdfToString(string $filePath): string
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);

        return $pdf->getText();
    }
}
