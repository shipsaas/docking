<?php

namespace App\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use RuntimeException;
use Mpdf\Mpdf;
use Throwable;

class MpdfRendererService extends AbstractPdfRendererService implements PdfRendererContract
{
    public function render(
        DocumentTemplate $documentTemplate,
        array $variables = [],
        array $metadata = []
    ): PdfRenderResult {
        $inputFile = $this->renderTemplate($documentTemplate, $variables);
        $outputFile = tempnam(sys_get_temp_dir(), 'mpdf_');

        try {
            $mPdf = $this->createMdpfInstance($metadata);

            $mPdf->WriteHTML(file_get_contents($inputFile));
            $mPdf->Output($outputFile);

            // filesize validation
            $fileSize = filesize($outputFile);
            if ($fileSize === false || $fileSize === 0) {
                throw new RuntimeException('Render failed - Filesize is 0 byte');
            }

            // store file
            $file = $this->saveFile($documentTemplate, $outputFile, $variables, $metadata);
            if (!$file) {
                return PdfRenderResult::error(new PdfRenderErrorOutcome(
                    PdfRenderErrorCode::STORE_FILE_FAILED
                ));
            }

            return PdfRenderResult::ok(new PdfRenderOkOutcome(
                $file
            ));
        } catch (Throwable $throwable) {
            report($throwable);

            return PdfRenderResult::error(new PdfRenderErrorOutcome(
                PdfRenderErrorCode::UNEXPECTED_ERROR
            ));
        }
    }

    private function createMdpfInstance(array $metadata = []): Mpdf
    {
        $mpdf = new Mpdf([
            'format' => $metadata['format'] ?? 'A4',
            'margin_left' => $metadata['margin-left'] ?? 15,
            'margin_right' => $metadata['margin-right'] ?? 15,
            'margin_top' => $metadata['margin-top'] ?? 16,
            'margin_bottom' => $metadata['margin-bottom'] ?? 16,
            'orientation' => $metadata['margin-orientation'] ?? 'P',
            'tempDir' => sys_get_temp_dir(),
        ]);
        $mpdf->setLogger(logger());
        $mpdf->useSubstitutions = false;
        $mpdf->simpleTables = true;
        $mpdf->SetProtection(['print']);
        $mpdf->SetDisplayMode('fullpage');

        return $mpdf;
    }
}
