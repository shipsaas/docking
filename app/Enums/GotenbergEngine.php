<?php

namespace App\Enums;

use App\Utils\EnumHelper;

enum GotenbergEngine: string
{
    use EnumHelper;

    case CHROMIUM = 'chromium';
    case LIBRE_OFFICE = 'libreoffice';
}
