<?php

namespace App\Utils;

use BackedEnum;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In as RuleIn;

/**
 * @mixin BackedEnum
 */
trait EnumHelper
{
    public static function getNames(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getRequestRule(): RuleIn
    {
        return Rule::in(static::getValues());
    }
}
