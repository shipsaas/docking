<?php

namespace Database\Factories;

use App\Models\DocumentFile;
use App\Models\DocumentTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFileFactory extends Factory
{
    protected $model = DocumentFile::class;

    public function definition(): array
    {
        return [
            'path' => $this->faker->word(),
            'size' => $this->faker->randomNumber(),
            'url' => $this->faker->url(),
            'variables' => [],
            'metadata' => [],
            'document_template_uuid' => fn () => DocumentTemplate::factory()->create(),
        ];
    }
}
