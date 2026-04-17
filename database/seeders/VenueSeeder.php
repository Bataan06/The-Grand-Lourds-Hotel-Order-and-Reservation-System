<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        Venue::truncate();
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

        $venues = [
            [
                'name'        => 'Green Acres',
                'description' => 'Perfect for intimate gatherings of 50-90 guests. Elegant venue with lush surroundings.',
                'image'       => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Grand Ballroom',
                'description' => 'Our premier venue for large events accommodating 100-300 guests. Grand and luxurious setting.',
                'image'       => null,
                'is_active'   => true,
            ],
        ];

        foreach ($venues as $venue) {
            Venue::create($venue);
        }
    }
}