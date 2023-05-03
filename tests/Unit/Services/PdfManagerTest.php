<?php

namespace Tests\Unit\Services;

use App\Enums\PdfService;
use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderers\GotenbergRendererService;
use App\Services\PdfRenderers\WkHtmlToPdfRendererService;
use App\Services\PdfRenderManager;
use Tests\TestCase;

class PdfManagerTest extends TestCase
{
    private PdfRenderManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = new PdfRenderManager();
    }

    public function testGetDriverReturnsTheDefaultDriver()
    {
        config([
            'docking.default-pdf-driver' => PdfService::GOTENBERG->value,
        ]);

        $driver = $this->manager->getDriver();

        $this->assertInstanceOf(GotenbergRendererService::class, $driver);
    }

    public function testGetDriverReturnsTheWantedDriver()
    {
        $driver = $this->manager->getDriver(PdfService::WK_HTML_TO_PDF->value);

        $this->assertInstanceOf(WkHtmlToPdfRendererService::class, $driver);
    }

    public function testGetDriverThrowsAnExceptionOnNonExistedDriver()
    {
        $this->expectExceptionMessage('PDF Driver "lol" is not supported');

        $this->manager->getDriver('lol');
    }

    public function testRenderReturnsTheRenderResult()
    {
        $gotenbergMocked = $this->createMock(GotenbergRendererService::class);
        $this->app->offsetSet(GotenbergRendererService::class, $gotenbergMocked);

        $gotenbergMocked->method('render')
            ->willReturn(PdfRenderResult::ok(
                new PdfRenderOkOutcome(new DocumentFile([
                    'document_template_uuid' => 'fake',
                ]))
            ));

        $result = $this->manager->render(
            new DocumentTemplate(),
            [
                'test' => 'hehe',
            ]
        );

        $this->assertTrue($result->isOk());
        $this->assertSame('fake', $result->getOkResult()->file->document_template_uuid);
    }
}
