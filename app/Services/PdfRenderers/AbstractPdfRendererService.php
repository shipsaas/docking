<?php

namespace Services\PdfRenderers;

use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Blade;

abstract class AbstractPdfRendererService
{
    public function renderTemplate(DocumentTemplate $template, array $variables = []): string
    {
        return Blade::render($template->template, $variables, deleteCachedView: true);
    }

    public function saveFile(
        DocumentTemplate $template,
        string $renderedFilePath
    ): DocumentFile {
        return DocumentFile::create([]);
    }
}
