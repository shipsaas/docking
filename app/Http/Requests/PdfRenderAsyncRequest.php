<?php

namespace App\Http\Requests;

use App\Enums\PdfService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PdfRenderAsyncRequest extends PdfRenderRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'webhook_url' => 'required|url',
        ];
    }
}
