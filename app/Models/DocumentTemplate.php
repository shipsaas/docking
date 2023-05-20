<?php

namespace App\Models;

use App\Enums\TemplatingMode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Throwable;

class DocumentTemplate extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;

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

    protected $primaryKey = 'uuid';

    public function documentFiles(): HasMany
    {
        return $this->hasMany(DocumentFile::class, 'document_template_uuid');
    }

    public function getTemplatingMode(): TemplatingMode
    {
        $rawMode = ($this->metadata['templating'] ?? null)
            ?: TemplatingMode::BLADE->value;

        return TemplatingMode::from($rawMode);
    }
}
