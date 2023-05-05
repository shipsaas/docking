<?php

namespace App\Http\Requests;

use App\Models\DocumentTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTemplateUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'category' => 'required|string',
            'template' => 'required|string',
            'default_variables' => 'required|array',
            'metadata' => 'required|array',
        ];
    }
}
