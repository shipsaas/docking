<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Database\Eloquent\Model;

class LanguageIndexRequest extends AbstractIndexRequest
{
    protected function getModel(): Model
    {
        return new Language();
    }

    protected function getAllowedSortColumns(): array
    {
        return [
            'code',
            'name',
            'created_at',
        ];
    }
}
