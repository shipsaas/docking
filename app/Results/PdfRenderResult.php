<?php

namespace App\Results;

use App\Results\PdfRenderOutcomes\PdfRenderErrorOutcome;
use App\Results\PdfRenderOutcomes\PdfRenderOkOutcome;
use NeverThrow\Result;

/**
 * @method PdfRenderOkOutcome getOkResult()
 * @method PdfRenderErrorOutcome getErrorResult()
 */
class PdfRenderResult extends Result
{
}
