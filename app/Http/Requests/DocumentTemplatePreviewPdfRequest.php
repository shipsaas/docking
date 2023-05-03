<?php

namespace App\Http\Requests;

use App\Enums\PdfService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentTemplatePreviewPdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'driver' => [
                'nullable',
                Rule::in(array_column(self::cases(), 'value')('', PdfService::cases())),
            ],
        ];
    }
}
