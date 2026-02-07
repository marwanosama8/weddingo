<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GalleryBlog>
 */
class GalleryBlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'love_reaction' => $this->faker->numberBetween(1,100),
            'sad_reaction' => $this->faker->numberBetween(1,100),
            'angry_reaction' => $this->faker->numberBetween(1,100),
            'appreciate_reaction' => $this->faker->numberBetween(1,100),
            'comment' => $this->faker->sentence(4)
        ];
    }
}
