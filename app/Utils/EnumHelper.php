<?php

namespace App\Utils;

use App\Enums\PdfService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In as RuleIn;

trait EnumHelper
{
    public static function getNames(): array
    {
        return array_column(PdfService::cases(), 'name');
    }

    public static function getValues(): array
    {
        return array_column(PdfService::cases(), 'value');
    }

    public static function getRequestRule(): RuleIn
    {
        return Rule::in(static::getValues());
    }
}
