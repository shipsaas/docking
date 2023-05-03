<?php

namespace App\Enums;

use App\Utils\EnumHelper;

enum PdfService: string
{
    use EnumHelper;

    case GOTENBERG = 'gotenberg';
    case WK_HTML_TO_PDF = 'wkhtmltopdf';
    case MPDF = 'mpdf';
}
