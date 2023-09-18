<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translation extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'translations';
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'key',
        'translation_group_id',
        'name',
        'text',
    ];

    protected $casts = [
        'text' => 'array',
    ];

    public function translationGroup(): BelongsTo
    {
        return $this->belongsTo(TranslationGroup::class, 'translation_group_id');
    }
}
