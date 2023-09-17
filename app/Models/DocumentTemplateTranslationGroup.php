<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DocumentTemplateTranslationGroup extends Pivot
{
    use HasUuids;
    use HasFactory;

    protected $table = 'document_template_translation_group';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'document_template_id',
        'translation_group_id',
    ];
}
