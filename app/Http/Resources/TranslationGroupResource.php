<?php

namespace App\Http\Resources;

use App\Models\TranslationGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TranslationGroup */
class TranslationGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->created_at?->toISOString(),
        ];
    }
}
