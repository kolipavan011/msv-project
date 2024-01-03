<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->paragraph(2),
            'path' => url('/video.mp4'),
            'poster' => url('poster.jpg'),
            'size' => '3.2MB',
            'folder_id' => 1,
            'user_id' => 1,
        ];
    }
}
