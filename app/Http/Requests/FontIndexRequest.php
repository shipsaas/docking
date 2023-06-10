<?php

namespace App\Http\Requests;

use App\Models\DocumentFile;
use App\Models\Font;
use Illuminate\Database\Eloquent\Model;

class FontIndexRequest extends AbstractIndexRequest
{
    protected function getModel(): Model
    {
        return new Font();
    }
}
