<?php

namespace Database\Seeders;

use App\Models\PadelMatch;
use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courts = Court::all();
        $users = User::where('role', 'user')->get();

        // Create matches for each court
        foreach ($courts->take(3) as $court) {
            PadelMatch::factory(5)->create([
                'court_id' => $court->id,
                'creator_id' => $users->random()->id,
            ]);
        }
    }
}
