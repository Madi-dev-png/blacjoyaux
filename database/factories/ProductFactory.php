<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = 'Sac '.fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 999999),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(20000, 90000),
            'stock' => fake()->numberBetween(0, 20),
            'color' => fake()->safeColorName(),
            'material' => 'Cuir grainé',
            'is_active' => true,
            'is_featured' => false,
            'seo_score' => 0,
        ];
    }
}
