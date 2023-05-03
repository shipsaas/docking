<?php

namespace App\Http\Requests;

use App\Enums\PdfService;
use Illuminate\Foundation\Http\FormRequest;

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
        ];
    }

    public function getMetadata(): array
    {
        return $this->input('metadata') ?: [];
    }
}
