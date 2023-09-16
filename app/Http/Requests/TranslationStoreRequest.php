<?php

namespace App\Http\Requests;

use App\Models\Translation;
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
                'exists:translation_groups,id',
            ],
            'name' => 'required|string',
            'text' => 'required|array',
        ];
    }
}
