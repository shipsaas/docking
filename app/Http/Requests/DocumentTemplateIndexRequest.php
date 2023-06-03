<?php

namespace App\Http\Requests;

use App\Models\DocumentTemplate;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplateIndexRequest extends AbstractIndexRequest
{
    protected function getModel(): Model
    {
        return new DocumentTemplate();
    }

    protected function getAllowedSortColumns(): array
    {
        return [
            'key',
            'category',
            'title',
            'created_at',
        ];
    }

    protected function getAllowedSearchColumns(): array
    {
        return [
            'key',
            'category',
            'title',
        ];
    }
}
