<?php

namespace App\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\PdfRenderResult;
use LogicException;

class MpdfRendererService extends AbstractPdfRendererService implements PdfRendererContract
{
    public function render(DocumentTemplate $documentTemplate, array $variables = [], array $metadata = []): PdfRenderResult
    {
        throw new LogicException('MPDF is not supported yet.');
    }
}
