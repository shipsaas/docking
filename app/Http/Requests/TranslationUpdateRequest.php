<?php

namespace App\Http\Requests;

use App\Models\Translation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TranslationUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        /**
         * @var Translation $currentTranslation
         */
        $currentTranslation = $this->route('translation');

        return [
            'key' => array_values(array_filter([
                'required',
                'string',
                $currentTranslation->key !== $this->input('key')
                    ? Rule::unique((new Translation())->getTable())
                    : null,
            ])),
            'translation_group_id' => [
                'required',
                'exists:translation_groups,id',
            ],
            'name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
