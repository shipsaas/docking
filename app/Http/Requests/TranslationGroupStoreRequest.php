<?php

namespace App\Http\Requests;

use App\Models\TranslationGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TranslationGroupStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                Rule::unique((new TranslationGroup())->getTable()),
            ],
            'name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
