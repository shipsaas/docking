<?php

namespace App\Http\Requests;

use App\Models\DocumentTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTemplateStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                Rule::unique((new DocumentTemplate())->getTable())
                    ->withoutTrashed(),
            ],
            'title' => 'required|string',
            'category' => 'required|string',
            'translation_groups' => 'nullable|array',
            'translation_groups.*' => 'uuid|exists:translation_groups,uuid',
        ];
    }
}
