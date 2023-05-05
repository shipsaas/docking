<?php

namespace App\Http\Resources;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;

/** @mixin DocumentTemplate */
class DocumentTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fields = [
            'uuid' => $this->uuid,
            'key' => $this->key,
            'category' => $this->category,
            'title' => $this->title,
            'updated_at' => $this->updated_at?->toISOString(),
        ];

        // TODO: remove this hack once the ShipSaaS/larvel-deferred-resources is up
        $isIndexEndpoint = $request->routeIs('document-templates.index');
        $additionalFields = [];

        if (!$isIndexEndpoint) {
            $additionalFields = [
                'template' => $this->template,
                'default_variables' => empty($this->default_variables) ? new stdClass() : $this->default_variables,
                'metadata' => empty($this->metadata) ? new stdClass() : $this->metadata,
                'created_at' => $this->created_at?->toISOString(),
            ];
        }

        return [
            ...$fields,
            ...$additionalFields,
        ];
    }
}
