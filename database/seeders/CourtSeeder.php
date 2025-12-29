<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create partners
        $partners = Partner::factory(3)->create();

        // Create courts for each partner
        foreach ($partners as $partner) {
            Court::factory(3)->create([
                'partner_id' => $partner->id,
                'admin_user_id' => 2, // court admin user
            ]);
        }
    }
}
