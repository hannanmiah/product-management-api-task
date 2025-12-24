<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 1, 1000),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement(['electronics', 'jewelery', "men's clothing", "women's clothing"]),
            'image' => fake()->imageUrl(),
            'rating_rate' => fake()->randomFloat(1, 1, 5),
            'rating_count' => fake()->numberBetween(0, 500),
            'api_product_id' => fake()->unique()->numberBetween(1, 1000),
        ];
    }
}
