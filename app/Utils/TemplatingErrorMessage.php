<?php

namespace App\Utils;

use App\Constants\SharedConstant;
use Illuminate\Support\Str;
use Throwable;

class TemplatingErrorMessage
{
    public static function resolveThrowable(Throwable $throwable): string
    {
        return sprintf(
            SharedConstant::RENDER_ERROR_MSG,
            Str::before($throwable->getMessage(), '(View:')
        );
    }
}
