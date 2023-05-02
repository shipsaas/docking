<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Throwable;

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

    protected $primaryKey = 'uuid';

    public function renderHtml(?array $variables = null): string
    {
        $variables ??= $this->default_variables;

        return rescue(
            fn () => Blade::render(
                $this->template,
                $variables,
                deleteCachedView: true
            ),
            fn (Throwable $error)
                => 'Failed to render HTML. Error: ' . Str::before($error->getMessage(), '(View:')
        );
    }
}
