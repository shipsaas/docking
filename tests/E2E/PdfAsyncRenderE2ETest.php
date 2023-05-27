<?php

namespace Tests\E2E;

use App\Enums\PdfService;
use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PdfAsyncRenderE2ETest extends TestCase
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

        // queue configuration
        config([
            'queue.default' => 'database',
        ]);
    }

    public function testRenderAsyncOk()
    {
        // 1. Push Job
        $this->json(
            'POST',
            'api/v1/document-templates/' . $this->template->uuid . '/pdfs-async',
            [
                'variables' => $this->template->default_variables,
                'metadata' => [
                    'driver' => PdfService::WK_HTML_TO_PDF->value,
                    'margin-top' => 15,
                    'margin-bottom' => 15,
                    'margin-left' => 20,
                    'margin-right' => 20,
                ],
                'webhook_url' => 'https://snorlax.shipsaas.tech/docking-webhook',
            ]
        )->assertOk();

        // 2. Assert jobs table
        $this->assertDatabaseCount('jobs', 1);

        // 3. Run Worker
        $this->artisan('queue:work --stop-when-empty');

        // 4. Assertions
        $latestFile = DocumentFile::latest()->first();

        $this->assertNotNull($latestFile);

        $latestWebhookRecord = Http::get('https://snorlax.shipsaas.tech/get-latest-webhook-request');

        $this->assertNotNull(
            $latestWebhookRecord->json('file_url')
        );
        $this->assertSame(
            $latestFile->url,
            $latestWebhookRecord->json('file_url')
        );
    }
}
