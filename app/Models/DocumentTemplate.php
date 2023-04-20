<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTemplate extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'document_templates';

    protected $fillable = [
        'key',
        'category',
        'title',
        'template',
        'default_variables',
        'metadata',
    ];

    protected $casts = [
        'default_variables' => 'array',
        'metadata' => 'array',
    ];
}
