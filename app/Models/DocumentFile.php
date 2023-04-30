<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function documentTemplate(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplate::class, 'document_template_uuid');
    }
}
