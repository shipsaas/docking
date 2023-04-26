<?php

namespace App\Http\Resources;

use App\Models\DocumentFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DocumentFile */
class DocumentFileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'document_template_uuid' => $this->document_template_uuid,
            'path' => $this->path,
            'size' => $this->size,
            'url' => $this->url,
            'created_at' => $this->created_at,
        ];
    }
}
