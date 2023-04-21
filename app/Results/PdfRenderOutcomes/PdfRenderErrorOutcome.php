<?php

namespace App\Results\PdfRenderOutcomes;

use App\Results\ErrorCodes\PdfRenderErrorCode;

class PdfRenderErrorOutcome
{
    public function __construct(
        public PdfRenderErrorCode $errorCode
    ) {
    }
}
