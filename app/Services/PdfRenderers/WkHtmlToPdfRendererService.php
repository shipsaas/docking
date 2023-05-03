<?php

namespace App\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderers\PdfRendererContract;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class WkHtmlToPdfRendererService extends AbstractPdfRendererService implements PdfRendererContract
{
    public const DEFAULT_PAGE_SIZE = 'a4';
    public const DEFAULT_MARGIN_TOP = '0mm';
    public const DEFAULT_MARGIN_BOTTOM = '0mm';
    public const DEFAULT_MARGIN_LEFT = '0mm';
    public const DEFAULT_MARGIN_RIGHT = '0mm';
    public const DEFAULT_ORIENTATION = 'Portrait';

    public function render(
        DocumentTemplate $documentTemplate,
        array $variables = [],
        array $metadata = []
    ): PdfRenderResult {
        $inputFile = $this->renderTemplate($documentTemplate, $variables);
        $outputFile = tempnam(sys_get_temp_dir(), 'wkhtmltopdf_');

        $metadata['page-size'] ??= static::DEFAULT_PAGE_SIZE;
        $metadata['margin-top'] ??= static::DEFAULT_MARGIN_TOP;
        $metadata['margin-bottom'] ??= static::DEFAULT_MARGIN_BOTTOM;
        $metadata['margin-left'] ??= static::DEFAULT_MARGIN_LEFT;
        $metadata['margin-top'] ??= static::DEFAULT_MARGIN_RIGHT;
        $metadata['orientation'] ??= static::DEFAULT_ORIENTATION;

        try {
            $result = Process::command("wkhtmltopdf \
                --page-size {$metadata['page-size']} \
                --margin-top {$metadata['margin-top']} \
                --margin-bottom {$metadata['margin-bottom']} \
                --margin-left {$metadata['margin-left']} \
                --margin-right {$metadata['margin-top']} \
                --orientation {$metadata['orientation']} \
                --enable-local-file-access \
                $inputFile \
                $outputFile
             ")->run();

            Log::info($result->output());
            Log::info($result->errorOutput());

            if (!$result->successful()) {
                return PdfRenderResult::error(new PdfRenderErrorOutcome(
                    PdfRenderErrorCode::UNEXPECTED_ERROR
                ));
            }

            $documentFile = $this->saveFile($documentTemplate, $outputFile, $variables, $metadata);
            if (!$documentFile) {
                return PdfRenderResult::error(new PdfRenderErrorOutcome(
                    PdfRenderErrorCode::STORE_FILE_FAILED
                ));
            }

            return PdfRenderResult::ok(new PdfRenderOkOutcome(
                $documentFile
            ));
        } catch (ProcessTimedOutException) {
            return PdfRenderResult::error(new PdfRenderErrorOutcome(
                PdfRenderErrorCode::TIMEOUT
            ));
        }
    }
}
