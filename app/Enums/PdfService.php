<?php

namespace App\Enums;

enum PdfService: string
{
    case GOTENBERG = 'gotenberg';
    case WK_HTML_TO_PDF = 'wkhtmltopdf';
    case MPDF = 'mpdf';
}
