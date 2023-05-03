<?php

namespace Database\Factories;

use App\Models\DocumentTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTemplateFactory extends Factory
{
    protected $model = DocumentTemplate::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->word(),
            'category' => $this->faker->word(),
            'title' => $this->faker->word(),
            'template' => $this->faker->randomHtml(),
            'default_variables' => [],
            'metadata' => [],
        ];
    }
}
