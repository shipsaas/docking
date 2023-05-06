<?php

namespace App\Http\Requests;

use App\Models\DocumentTemplate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTemplateStoreRequest extends FormRequest
{
    protected function failedAuthorization()
    {
        throw new AuthorizationException("We don't support creating new template for LiveMode test. Sorries.");
    }

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
        ];
    }
}
