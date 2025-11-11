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
        
            'name' => $this->faker->words(3, true), 
            'price' => $this->faker->numberBetween(15000, 50000),
            'category' => $this->faker->randomElement(['Makanan', 'Minuman']),
            'image_url' => $this->faker->imageUrl(640, 480, 'food')
        ];
    }
}
