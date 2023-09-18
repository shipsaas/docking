<?php

namespace Database\Factories;

use App\Models\TranslationGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationGroupFactory extends Factory
{
    protected $model = TranslationGroup::class;

    public function definition(): array
    {
        return [
            'key' => fake()->sentence(5),
            'name' => fake()->sentence(5),
            'description' => fake()->realText(50),
        ];
    }
}
