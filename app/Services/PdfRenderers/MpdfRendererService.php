<?php

namespace App\Services\PdfRenderers;

use App\Models\DocumentTemplate;
use App\Models\Font;
use App\Results\ErrorCodes\PdfRenderErrorCode;
use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use App\Results\PdfRenderResult;
use Illuminate\Support\Facades\Storage;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
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
        if (app()->runningUnitTests()
            && isset($metadata['use-test-instance'])
            && $metadata['use-test-instance'] === true
        ) {
            return app('mpdf-testing');
        }

        $useCustomFonts = isset($metadata['custom-fonts']) && is_array($metadata['custom-fonts']);

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $defaultFontConfig = (new FontVariables())->getDefaults();

        $mpdf = new Mpdf([
            'format' => $metadata['page-size'] ?? 'A4',
            'margin_left' => $metadata['margin-left'] ?? 15,
            'margin_right' => $metadata['margin-right'] ?? 15,
            'margin_top' => $metadata['margin-top'] ?? 16,
            'margin_bottom' => $metadata['margin-bottom'] ?? 16,
            'orientation' => $metadata['orientation'] ?? 'P',
            'tempDir' => sys_get_temp_dir(),
            'fontDir' => [
                ...$defaultConfig['fontDir'],
                ...($useCustomFonts ? [storage_path('app/fonts')] : []),
            ],
            'fontdata' => [
                ...$defaultFontConfig['fontdata'],
                ...($useCustomFonts ? $this->loadFonts($metadata['custom-fonts']) : []),
            ],
        ]);

        $mpdf->setLogger(logger());
        $mpdf->useSubstitutions = false;
        $mpdf->simpleTables = true;
        $mpdf->SetDisplayMode('fullpage');

        // this will generate an unreadable PDF
        if (isset($metadata['encrypt-pdf']) && $metadata['encrypt-pdf'] === true) {
            $mpdf->SetProtection(['print']);
        }

        return $mpdf;
    }

    private function loadFonts(array $customFonts): array
    {
        return Font::whereIn('key', $customFonts)
            ->get(['key', 'path'])
            ->mapWithKeys(fn (Font $font) => [
                $font->key => [
                    'R' => basename($font->path),
                    'useOTL' => 0x00,
                ],
            ])
            ->toArray();
    }
}
