<?php

namespace Database\Factories;

use App\Models\Font;
use Illuminate\Database\Eloquent\Factories\Factory;

class FontFactory extends Factory
{
    protected $model = Font::class;

    public function definition(): array
    {
        return [
            'key' => fake()->words(5),
            'name' => fake()->words(5),
            'path' => fake()->filePath(),
        ];
    }
}
