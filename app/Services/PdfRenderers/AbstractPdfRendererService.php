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
    public function renderTemplate(DocumentTemplate $template, array $variables = []): string
    {
        return Blade::render($template->template, $variables, deleteCachedView: true);
    }

    public function saveFile(
        DocumentTemplate $template,
        string $renderedFilePath,
        array $variables = [],
        array $metadata = []
    ): ?DocumentFile {
        // if it is a URL, download
        if (Str::startsWith($renderedFilePath, 'http')) {
            Http::get($renderedFilePath);
        }

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
            'url' => Storage::publicUrl($storedFileName),
            'size' => $file->getSize() ?: 0,
            'variables' => $variables,
            'metadata' => $metadata,
        ]);
    }
}
