<?php

namespace App\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\PdfRenderResult;

interface PdfRendererContract
{
    public function render(
        DocumentTemplate $documentTemplate,
        array $variables = [],
        array $metadata = []
    ): PdfRenderResult;
}
