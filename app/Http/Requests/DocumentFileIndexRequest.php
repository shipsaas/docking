<?php

namespace App\Http\Requests;

use App\Models\DocumentFile;
use Illuminate\Database\Eloquent\Model;

class DocumentFileIndexRequest extends AbstractIndexRequest
{
    protected function getModel(): Model
    {
        return new DocumentFile();
    }
}
