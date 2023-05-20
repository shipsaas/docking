<?php

namespace App\Services\TemplatingServices;

use App\Models\DocumentTemplate;
use Illuminate\Mail\Markdown;

class MarkdownTemplatingService implements TemplatingServiceContract
{
    public function __construct(private BladeTemplatingService $bladeTemplatingService)
    {
    }

    /**
     * Before passing things to markdown stage, we need to resolve the template & variables
     * using the normal Blade render
     *
     * After we got the rendered text, pass it to Markdown.
     */
    public function renderHtml(DocumentTemplate $template, array $variables): string
    {
        $renderedMarkdown = $this->bladeTemplatingService->renderHtml($template, $variables);

        return Markdown::parse($renderedMarkdown)->toHtml();
    }
}
