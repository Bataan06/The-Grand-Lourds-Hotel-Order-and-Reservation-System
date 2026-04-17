<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        Event::truncate();
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

        $events = [
            [
                'name'        => 'Birthday / Christening',
                'description' => 'Celebrate your special day with our curated birthday and christening packages.',
                'image'       => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Wedding',
                'description' => 'Make your dream wedding a reality with our elegant wedding packages.',
                'image'       => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'Conference',
                'description' => 'Professional conference and corporate event packages.',
                'image'       => null,
                'is_active'   => true,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}