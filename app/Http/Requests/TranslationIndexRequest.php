<?php

namespace App\Http\Requests;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;

class TranslationIndexRequest extends AbstractIndexRequest
{
    protected function getModel(): Model
    {
        return new Translation();
    }

    protected function getAllowedSortColumns(): array
    {
        return [
            'key',
            'name',
            'created_at',
        ];
    }
}
