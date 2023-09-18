<?php

namespace App\Http\Requests;

use App\Enums\PdfService;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PdfRenderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'variables' => 'nullable|array',
            'metadata' => 'nullable|array',
            'metadata.driver' => [
                'nullable',
                PdfService::getRequestRule(),
            ],
            'metadata.language' => [
                'nullable',
                Rule::in(Language::pluck('code')),
            ],
        ];
    }

    public function getMetadata(): array
    {
        return $this->input('metadata') ?: [];
    }
}
