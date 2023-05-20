<?php

namespace Tests\Unit\Services\TemplatingServices;

use App\Models\DocumentTemplate;
use App\Services\TemplatingServices\BladeTemplatingService;
use Tests\TestCase;

class BladeTemplatingServiceTest extends TestCase
{
    public function testRenderReturnsTheRenderedHtml()
    {
        $template = new DocumentTemplate([
            'template' => 'Hello {{ $partner }}',
        ]);

        $service = new BladeTemplatingService();
        $renderedHtml = $service->renderHtml($template, [
            'partner' => 'Seth',
        ]);

        $this->assertSame('Hello Seth', $renderedHtml);
    }

    public function testRenderOnErrorReturnsTheErrorString()
    {
        $template = new DocumentTemplate([
            'template' => 'Hello {{ $partner }}',
        ]);

        $service = new BladeTemplatingService();
        $renderedHtml = $service->renderHtml($template, [
            'name' => 'Seth',
        ]);

        $this->assertStringStartsWith('Failed to', $renderedHtml);
    }
}
