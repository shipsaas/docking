<?php

namespace Database\Factories;

use App\Models\Translation;
use App\Models\TranslationGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'translation_group_id' => fn () => TranslationGroup::factory()->create(),
            'key' => fake()->sentence(5),
            'name' => fake()->sentence(5),
            'text' => [],
        ];
    }
}
