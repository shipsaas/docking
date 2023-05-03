<?php

namespace App\Services\PdfRenderers;

use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class AbstractPdfRendererService
{
    protected function renderTemplate(DocumentTemplate $template, array $variables = []): string
    {
        $inputFile = tempnam(sys_get_temp_dir(), 'rendered_html_template');
        rename($inputFile, $inputFile .= '.html');

        $htmlRendered = Blade::render($template->template, $variables, deleteCachedView: true);

        file_put_contents($inputFile, $htmlRendered);

        return $inputFile;
    }

    protected function saveFile(
        DocumentTemplate $template,
        string $renderedFilePath,
        array $variables = [],
        array $metadata = []
    ): ?DocumentFile {
        // store to default Storage
        $file = new File($renderedFilePath);
        $storedFileName = Storage::putFile('rendered-pdfs', $file, [
            'visibility' => 'public',
        ]);

        if ($storedFileName === false) {
            return null;
        }

        return DocumentFile::create([
            'document_template_uuid' => $template->uuid,
            'path' => $storedFileName,
            'url' => Storage::url($storedFileName),
            'size' => $file->getSize() ?: 0,
            'variables' => $variables,
            'metadata' => $metadata,
            'is_preview_file' => isset($metadata['is_preview']),
        ]);
    }
}
