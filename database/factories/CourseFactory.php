<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\CoursePrefix;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'prefix' => fake()->randomElement(CoursePrefix::cases())->value,
        'number' => fake()->numberBetween(100, 499),
        'title' => fake()->sentence(3),
        ];
    }
}
