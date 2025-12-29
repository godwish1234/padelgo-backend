<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\User;
use App\Models\PadelMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PadelMatch>
 */
class MatchFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'court_id' => Court::factory(),
            'creator_id' => User::where('role', 'user')->first()?->id ?? User::factory()->create(['role' => 'user'])->id,
            'title' => fake()->sentence(3),
            'description' => fake()->text(100),
            'match_date_time' => fake()->dateTimeBetween('+1 days', '+30 days'),
            'max_players' => 4,
            'current_players' => 1,
            'skill_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            'match_type' => fake()->randomElement(['friendly', 'competitive']),
            'status' => 'open',
            'notes' => fake()->text(50),
        ];
    }
}
