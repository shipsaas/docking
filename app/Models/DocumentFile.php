<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
    use HasUuids;

    protected $table = 'document_files';

    protected $fillable = [
        'document_template_uuid',
        'path',
        'url',
        'size',
        'variables',
        'metadata',
    ];

    protected $casts = [
        'size' => 'integer',
        'variables' => 'array',
        'metadata' => 'array',
    ];

    protected $primaryKey = 'uuid';
}
