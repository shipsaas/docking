<?php

namespace App\Results\ErrorCodes;

enum PdfRenderErrorCode: string
{
    case UNEXPECTED_ERROR = 'UNEXPECTED_ERROR';
    case TIMEOUT = 'TIMEOUT';
    case STORE_FILE_FAILED = 'STORE_FILE_FAILED';
}
