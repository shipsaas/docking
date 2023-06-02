<?php

namespace App\Services\PdfRenderers;

use App\Enums\GotenbergEngine;
use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GotenbergRendererService extends AbstractPdfRendererService implements PdfRendererContract
{
    public const TEMP_FILE_PREFIX = 'gotenberg_';
    public const DEFAULT_ENGINE = GotenbergEngine::CHROMIUM->value;

    public function __construct(
        protected readonly string $gotenbergEndpoint
    ) {
    }

    public function render(
        DocumentTemplate $documentTemplate,
        array $variables = [],
        array $metadata = []
    ): PdfRenderResult {
        // render template
        $inputFile = $this->renderTemplate($documentTemplate, $variables);

        // request to gotenberg
        $driver = ($metadata['engine'] ?? null) ?: static::DEFAULT_ENGINE;
        $response = Http::asMultipart()
            ->attach('files', fopen($inputFile, 'r'), 'index.html')
            ->post(
                $this->getEndpointByEngine($driver),
                $this->resolveMetadata($metadata),
            );

        // assert
        $contentDisposition = $response->header('Content-Disposition');
        if (!$response->successful() || !$contentDisposition) {
            return PdfRenderResult::error(new PdfRenderErrorOutcome(
                PdfRenderErrorCode::UNEXPECTED_ERROR
            ));
        }

        // gotenberg will return a "downloadable" response, so we need to save
        // the response into a temp file
        $outputFile = $this->writeResponseToTempFile($response);

        // store to default storage (local or S3)
        $documentFile = $this->saveFile($documentTemplate, $outputFile, $variables, $metadata);
        if (!$documentFile) {
            return PdfRenderResult::error(new PdfRenderErrorOutcome(
                PdfRenderErrorCode::STORE_FILE_FAILED
            ));
        }

        return PdfRenderResult::ok(new PdfRenderOkOutcome(
            $documentFile
        ));
    }

    private function getEndpointByEngine(string $engine): string
    {
        return match ($engine) {
            GotenbergEngine::CHROMIUM->value
                => "{$this->gotenbergEndpoint}/forms/chromium/convert/html",
            GotenbergEngine::LIBRE_OFFICE->value
                => "{$this->gotenbergEndpoint}/forms/libreoffice/convert",
        };
    }

    private function writeResponseToTempFile(Response $response): string
    {
        $outputFile = tempnam(sys_get_temp_dir(), static::TEMP_FILE_PREFIX);

        $outputFileStream = fopen($outputFile, 'w');
        fwrite($outputFileStream, $response->body());
        fclose($outputFileStream);

        return $outputFile;
    }

    private function resolveMetadata(array $metadata): array
    {
        $gotenbergMetadata = [];

        $gotenbergMetadata['pageWidth'] ??= $metadata['page-width'] ?? null;
        $gotenbergMetadata['paperHeight'] ??= $metadata['page-height'] ?? null;
        $gotenbergMetadata['marginTop'] ??= $metadata['margin-top'] ?? null;
        $gotenbergMetadata['marginBottom'] ??= $metadata['margin-bottom'] ?? null;
        $gotenbergMetadata['marginLeft'] ??= $metadata['margin-left'] ?? null;
        $gotenbergMetadata['marginRight'] ??= $metadata['margin-right'] ?? null;
        $gotenbergMetadata['preferCssPageSize'] ??= $metadata['prefer-css-page-size'] ?? null;
        $gotenbergMetadata['printBackground'] ??= $metadata['print-background'] ?? null;
        $gotenbergMetadata['omitBackground'] ??= $metadata['omit-background'] ?? null;
        $gotenbergMetadata['landscape'] ??= $metadata['landscape'] ?? null;
        $gotenbergMetadata['scale'] ??= $metadata['scale'] ?? null;
        $gotenbergMetadata['nativePageRanges'] ??= $metadata['native-page-ranges'] ?? null;

        return array_filter($gotenbergMetadata);
    }
}
