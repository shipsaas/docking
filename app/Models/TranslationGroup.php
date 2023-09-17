<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranslationGroup extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'translation_groups';

    protected $fillable = [
        'key',
        'name',
        'description',
    ];

    protected $primaryKey = 'uuid';

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class, 'translation_group_id');
    }
}
