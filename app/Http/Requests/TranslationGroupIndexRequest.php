<?php

namespace App\Http\Requests;

use App\Models\TranslationGroup;
use Illuminate\Database\Eloquent\Model;

class TranslationGroupIndexRequest extends AbstractIndexRequest
{
    protected function getModel(): Model
    {
        return new TranslationGroup();
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
