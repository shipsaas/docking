<?php

namespace App\Http\Resources;

use App\Models\Translation;
use App\Models\TranslationGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Translation */
class TranslationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'key' => $this->key,
            'name' => $this->name,
            'text' => $this->text,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->created_at?->toISOString(),

            'translation_group' => self::whenLoaded(
                'translationGroup',
                fn () => TranslationGroupResource::make($this->translationGroup),
            ),
        ];
    }
}
