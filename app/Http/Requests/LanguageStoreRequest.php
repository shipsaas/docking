<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LanguageStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                Rule::unique((new Language())->getTable()),
            ],
            'name' => 'required|string',
        ];
    }
}
