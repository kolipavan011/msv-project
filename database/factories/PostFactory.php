<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'body' => fake()->text(),
            'featured_image' => fake()->imageUrl(480, 360),
            'seo_title' => fake()->sentence(),
            'seo_desc' => fake()->sentence(),
            'post_type' => 1,
            'user_id' => 1
        ];
    }
}
