<?php

namespace App\Http\Requests;

use App\Models\DocumentTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTemplateDuplicateRequest extends FormRequest
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
        ];
    }
}
