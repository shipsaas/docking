<?php

namespace App\Http\Resources;

use App\Models\Font;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Font */
class FontResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'key' => $this->key,
            'name' => $this->name,
            'path' => $this->path,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
