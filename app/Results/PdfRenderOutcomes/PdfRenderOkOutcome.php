<?php

namespace App\Results\PdfRenderOutcomes;

use App\Models\DocumentFile;

class PdfRenderOkOutcome
{
    public function __construct(
        public DocumentFile $file
    ) {
    }
}
