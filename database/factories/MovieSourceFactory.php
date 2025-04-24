<?php

namespace Database\Factories;

use App\Objects\MovieSourceType;
use App\Objects\MovieType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MovieSource>
 */
class MovieSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source' => MovieSourceType::OMDb,
            'source_id' => 'tt'.fake()->randomNumber(7),
            'title' => fake()->text(20),
            'year' => fake()->date('Y'),
            'image_url' => fake()->imageUrl(),
            'type' => fake()->randomElement([MovieType::Movie, MovieType::Series, MovieType::Episode, MovieType::Game])
        ];
    }
}
