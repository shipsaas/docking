<?php

namespace App\Models;

use App\Enums\TemplatingMode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function translationGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            TranslationGroup::class,
            foreignPivotKey: 'document_template_id',
            relatedPivotKey: 'translation_group_id'
        )->using(DocumentTemplateTranslationGroup::class);
    }

    public function getTemplatingMode(): TemplatingMode
    {
        $rawMode = ($this->metadata['templating'] ?? null)
            ?: TemplatingMode::BLADE->value;

        return TemplatingMode::from($rawMode);
    }

    public static function getByUuidOrKey(string $value): DocumentTemplate
    {
        return DocumentTemplate::where(function ($q) use ($value) {
            $q->orWhere('uuid', $value)->orWhere('key', $value);
        })->firstOrFail();
    }
}
