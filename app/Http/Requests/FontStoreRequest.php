<?php

namespace App\Http\Requests;

use App\Models\Font;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FontStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                Rule::unique((new Font())->getTable())
                    ->withoutTrashed(),
            ],
            'name' => 'required|string',
            'font' => [
                'required',
                'mimetypes:font/ttf,font/woff,font/woff2,font/otf'
            ],
        ];
    }
}
