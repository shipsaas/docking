<?php

namespace Tests\E2E;

use App\Enums\PdfService;
use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use Tests\TestCase;

class AwsS3E2ETest extends TestCase
{
    protected DocumentTemplate $template;

    protected function setUp(): void
    {
        parent::setUp();

        $this->template = DocumentTemplate::factory()->create([
            'template' => file_get_contents(__DIR__ . '/../Integration/__fixtures__/invoice.html'),
            'default_variables' => json_decode(
                file_get_contents(__DIR__ . '/../Integration/__fixtures__/invoice.json'),
                true
            ),
        ]);

        config([
            'filesystems.default' => 's3',
        ]);
    }

    public function testRenderStoresFileOnS3()
    {
        // 1. Render
        $this->json(
            'POST',
            'api/v1/document-templates/' . $this->template->uuid . '/pdfs',
            [
                'variables' => $this->template->default_variables,
                'metadata' => [
                    'driver' => PdfService::MPDF->value,
                    'margin-top' => 15,
                    'margin-bottom' => 15,
                    'margin-left' => 20,
                    'margin-right' => 20,
                ],
            ]
        )->assertOk();

        // 2. Assertions
        $latestFile = DocumentFile::latest()->first();
        $this->assertNotNull($latestFile);

        $this->assertStringContainsString(
            'amazonaws.com/rendered-pdfs/',
            $latestFile->url
        );
    }
}
