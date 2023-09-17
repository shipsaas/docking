<?php

namespace Database\Factories;

use App\Models\DocumentTemplate;
use App\Models\DocumentTemplateTranslationGroup;
use App\Models\TranslationGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTemplateTranslationGroupFactory extends Factory
{
    protected $model = DocumentTemplateTranslationGroup::class;

    public function definition(): array
    {
        return [
            'document_template_id' => fn () => DocumentTemplate::factory()->create(),
            'translation_group_id' => fn () => TranslationGroup::factory()->create(),
        ];
    }
}
