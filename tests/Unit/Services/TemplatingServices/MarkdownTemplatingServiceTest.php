<?php

namespace Tests\Unit\Services\TemplatingServices;

use App\Models\DocumentTemplate;
use App\Services\TemplatingServices\BladeTemplatingService;
use App\Services\TemplatingServices\MarkdownTemplatingService;
use Tests\TestCase;

class MarkdownTemplatingServiceTest extends TestCase
{
    public function testRenderReturnsTheRenderedHtml()
    {
        $template = new DocumentTemplate([
            'template' => file_get_contents(__DIR__ . '/../../../Integration/__fixtures__/email.md'),
        ]);

        $service = app(MarkdownTemplatingService::class);
        $renderedHtml = $service->renderHtml($template, [
            'name' => 'Seth',
        ]);

        $this->assertStringContainsString('Hello <strong>Seth</strong>', $renderedHtml);
        $this->assertStringContainsString('<h1>', $renderedHtml);
        $this->assertStringContainsString('<h2>', $renderedHtml);
        $this->assertStringContainsString('<h3>', $renderedHtml);
        $this->assertStringContainsString('<ul>', $renderedHtml);
        $this->assertStringContainsString('</ul>', $renderedHtml);
        $this->assertStringContainsString('<li>php', $renderedHtml);
        $this->assertStringContainsString('<li>pdf rendering', $renderedHtml);
        $this->assertStringContainsString('<li>document template', $renderedHtml);
    }

    public function testRenderOnErrorReturnsTheErrorString()
    {
        $template = new DocumentTemplate([
            'template' => 'Hello {{ $partner }}',
        ]);

        $service = app(MarkdownTemplatingService::class);
        $renderedHtml = $service->renderHtml($template, [
            'name' => 'Seth',
        ]);

        $this->assertStringContainsString('Failed to', $renderedHtml);
    }
}
