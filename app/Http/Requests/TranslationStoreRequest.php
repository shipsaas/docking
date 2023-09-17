<?php

namespace App\Http\Requests;

use App\Models\Translation;
use App\Rules\TranslationTextRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TranslationStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                Rule::unique((new Translation())->getTable()),
            ],
            'translation_group_id' => [
                'required',
                'exists:translation_groups,uuid',
            ],
            'name' => 'required|string',
            'text' => [
                'required',
                'array',
                new TranslationTextRule(),
            ],
        ];
    }
}
