<?php

namespace App\Http\Resources;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentTemplateCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(fn (DocumentTemplate $template) => [
                'uuid' => $template->uuid,
                'key' => $template->key,
                'category' => $template->category,
                'title' => $template->title,
                'created_at' => $template->created_at,
                'updated_at' => $template->updated_at,
            ]),
        ];
    }
}
