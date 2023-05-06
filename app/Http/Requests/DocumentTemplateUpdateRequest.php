<?php

namespace App\Http\Requests;

use App\Models\DocumentTemplate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTemplateUpdateRequest extends FormRequest
{
    protected function failedAuthorization(): void
    {
        throw new AuthorizationException("We don't support updating new template for LiveMode test. Sorries.");
    }

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
