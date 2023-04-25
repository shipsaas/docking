<?php

namespace Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use App\Services\PdfRenderers\PdfRendererContract;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
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
        $inputFile = tempnam(sys_get_temp_dir() . '/rendered_template', 'wkhtmltopdf_');
        $outputFile = tempnam(sys_get_temp_dir() . '/pdf', 'wkhtmltopdf_');

        $pageSize = $metadata['page-size'] ?? static::DEFAULT_PAGE_SIZE;
        $marginTop = $metadata['margin-top'] ?? static::DEFAULT_MARGIN_TOP;
        $marginBottom = $metadata['margin-bottom'] ?? static::DEFAULT_MARGIN_BOTTOM;
        $marginLeft = $metadata['margin-left'] ?? static::DEFAULT_MARGIN_LEFT;
        $marginRight = $metadata['margin-top'] ?? static::DEFAULT_MARGIN_RIGHT;
        $orientation = $metadata['orientation'] ?? static::DEFAULT_ORIENTATION;

        // store rendered view
        file_put_contents($inputFile, $this->renderTemplate($documentTemplate, $variables));

        try {
            $result = Process::command("
                wkhtmltopdf
                --page-size $pageSize
                --margin-top $marginTop
                --margin-bottom $marginBottom
                --margin-left $marginLeft
                --margin-right $marginRight
                 --orientation $orientation
                 $inputFile
                 $outputFile
             ")->run();

            if (!$result->successful()) {
                return PdfRenderResult::error(new PdfRenderErrorOutcome(
                    PdfRenderErrorCode::UNEXPECTED_ERROR
                ));
            }

            $documentFile = $this->saveFile($documentTemplate, $outputFile);

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
