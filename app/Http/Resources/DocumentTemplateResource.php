<?php

namespace App\Http\Resources;

use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DocumentTemplate */
class DocumentTemplateResource extends JsonResource
{
    protected array $excludedColumns = [];

    public function toArray(Request $request): array
    {
        // TODO: remove this hack once the ShipSaaS/larvel-deferred-resources is up
        $isIndexEndpoint = $request->routeIs('document-templates.index');

        return [
            'uuid' => $this->uuid,
            'key' => $this->key,
            'category' => $this->category,
            'title' => $this->title,
            'template' => $isIndexEndpoint ? null : $this->template,
            'default_variables' => $isIndexEndpoint ? null : $this->default_variables,
            'metadata' => $isIndexEndpoint ? null : $this->metadata,
            'created_at' => $isIndexEndpoint ? null : $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function setExcludedColumns(array $columns): self
    {
        $this->excludedColumns = $columns;

        return $this;
    }
}
