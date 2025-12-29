<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Court>
 */
class CourtFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $cities = ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao', 'Malaga'];

        return [
            'partner_id' => Partner::factory(),
            'admin_user_id' => User::where('role', 'court_admin')->first()?->id ?? User::factory()->create(['role' => 'court_admin'])->id,
            'name' => fake()->name(),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement($cities),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'phone' => fake()->phoneNumber(),
            'facilities' => ['parking', 'cafe', 'pro_shop', 'changing_rooms'],
            'description' => fake()->text(200),
            'is_active' => true,
        ];
    }
}
