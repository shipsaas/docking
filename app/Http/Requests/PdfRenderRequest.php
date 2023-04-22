<?php

namespace App\Http\Requests;

use App\Enums\PdfService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PdfRenderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'variables' => 'nullable|array',
            'metadata' => 'nullable|array',
            'metadata.driver' => [
                'nullable',
                Rule::in(PdfService::cases()),
            ],
        ];
    }

    public function getMetadata(): array
    {
        $metadata = $this->input('metadata') ?: [];
        $metadata['driver'] = $metadata['driver'] ?? null;

        return $metadata;
    }
}
