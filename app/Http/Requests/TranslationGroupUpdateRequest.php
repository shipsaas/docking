<?php

namespace App\Http\Requests;

use App\Models\TranslationGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TranslationGroupUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
