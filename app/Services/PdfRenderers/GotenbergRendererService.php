<?php

namespace App\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderers\PdfRendererContract;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GotenbergRendererService extends AbstractPdfRendererService implements PdfRendererContract
{
    public const TEMP_FILE_PREFIX = 'gotenberg_';
    public const DEFAULT_DRIVER = 'chromium';

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
        file_put_contents($inputFile, $this->renderTemplate($documentTemplate, $variables));

        // request to gotenberg
        $driver = $metadata['driver'] ?? static::DEFAULT_DRIVER;
        $response = Http::asMultipart()
            ->attach('files', fopen($inputFile, 'r'), 'index.html')
            ->post($this->getEndpointByDriver($driver));

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

    private function getEndpointByDriver(string $driver): string
    {
        return match ($driver) {
            'chromium' => $this->gotenbergEndpoint . '/forms/chromium/convert/html',
            'libreoffice' => $this->gotenbergEndpoint . '/forms/libreoffice/convert',
        };
    }

    private function writeResponseToTempFile(Response $response): string
    {
        $outputFile = tempnam(sys_get_temp_dir() . '/rendered_pdfs', static::TEMP_FILE_PREFIX);

        $outputFileStream = fopen($outputFile, 'w');
        fwrite($outputFileStream, $response->body());
        fclose($outputFileStream);

        return $outputFile;
    }
}
