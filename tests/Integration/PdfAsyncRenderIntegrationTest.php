<?php

namespace Tests\Integration;

use App\Enums\PdfService;
use App\Models\DocumentTemplate;
use Tests\TestCase;

class PdfAsyncRenderIntegrationTest extends TestCase
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
                    'pageWidth' => 5,
                    'marginTop' => 15,
                    'marginBottom' => 15,
                    'marginLeft' => 20,
                    'marginRight' => 20,
                ],
                'webhook_url' => route('tests.notifications'),
            ]
        )->assertOk();

        // 2. Assert jobs table
        $this->assertDatabaseCount('jobs', 1);

        // 3. Run Worker
        $this->artisan('queue:work --stop-when-empty');

        // 4. Assertions
        $this->assertTrue(config('notifications.notified'));
        $this->assertNotNull(config('notifications.url'));
        $this->assertStringStartsWith('http', config('notifications.url'));
    }
}
