<?php

namespace App\Services;

use App\Enums\TemplatingMode;
use App\Models\DocumentTemplate;
use App\Services\TemplatingServices\BladeTemplatingService;
use App\Services\TemplatingServices\MarkdownTemplatingService;
use App\Services\TemplatingServices\TemplatingServiceContract;

class TemplatingRenderManager
{
    protected TemplatingMode $mode;

    public function setMode(TemplatingMode $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function renderHtml(DocumentTemplate $template, array $variables): string
    {
        /** @var TemplatingServiceContract $instance */
        $instance = match ($this->mode) {
            TemplatingMode::BLADE => app(BladeTemplatingService::class),
            TemplatingMode::MARKDOWN => app(MarkdownTemplatingService::class),
        };

        return $instance->renderHtml($template, $variables);
    }
}
