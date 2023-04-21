<?php

namespace Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderers\PdfRendererContract;

class WkHtmlToPdfRendererService implements PdfRendererContract
{
    public function render(DocumentTemplate $documentTemplate, array $variables = [], array $metadata = []): PdfRenderResult
    {
        // TODO: Implement render() method.
    }
}
