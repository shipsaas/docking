<?php

namespace App\Services;

use App\Enums\PdfService;
use App\Models\DocumentTemplate;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderers\PdfRendererContract;
use RuntimeException;
use App\Services\PdfRenderers\GotenbergRendererService;
use App\Services\PdfRenderers\WkHtmlToPdfRendererService;

class PdfRenderManager
{
    public function getDriver(?string $driver = null): PdfRendererContract
    {
        $driver ??= config('docking.default-pdf-driver');

        return match ($driver) {
            PdfService::GOTENBERG->value => app(GotenbergRendererService::class),
            PdfService::WK_HTML_TO_PDF->value => app(WkHtmlToPdfRendererService::class),
            // TODO: phase 2
            // PdfService::MPDF->value => app(MpdfRendererService::class),
            default => throw new RuntimeException("PDF Driver \"$driver\" is not supported")
        };
    }

    public function render(
        DocumentTemplate $template,
        array $variables = [],
        array $metadata = []
    ): PdfRenderResult {
        return $this->getDriver($metadata['driver'] ?? null)
            ->render($template, $variables, $metadata);
    }
}
