<?php

namespace App\Rules;

use App\Models\Language;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TranslationTextRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allLanguageCodes = Language::pluck('code')->toArray();

        $clientLangCodes = array_keys($value);

        $isOk = empty(array_diff($allLanguageCodes, $clientLangCodes));

        if (!$isOk) {
            $fail('Missing languageCode in the object. Required langCodes: ' . implode(', ', $allLanguageCodes));
        }
    }
}
