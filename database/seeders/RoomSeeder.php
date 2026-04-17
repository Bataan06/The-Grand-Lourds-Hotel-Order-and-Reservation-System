<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('rooms')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('rooms')->insert([
            ['name' => 'Deluxe Room 1', 'type' => 'deluxe', 'capacity' => 2, 'price' => 2500, 'description' => 'Deluxe room for 2 persons.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Deluxe Room 2', 'type' => 'deluxe', 'capacity' => 2, 'price' => 2500, 'description' => 'Deluxe room for 2 persons.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Family Room 1', 'type' => 'family', 'capacity' => 6, 'price' => 4500, 'description' => 'Family room for up to 6 persons.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Family Room 2', 'type' => 'family', 'capacity' => 6, 'price' => 4500, 'description' => 'Family room for up to 6 persons.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quad Room 1', 'type' => 'quad', 'capacity' => 8, 'price' => 6000, 'description' => 'Quad room for up to 8 persons.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quad Room 2', 'type' => 'quad', 'capacity' => 8, 'price' => 6000, 'description' => 'Quad room for up to 8 persons.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KTV Room (1-4 pax)', 'type' => 'ktv', 'capacity' => 4, 'price' => 2600, 'description' => 'KTV room for 1-4 persons. 3 hours consumable.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KTV Room (4-8 pax)', 'type' => 'ktv', 'capacity' => 8, 'price' => 3200, 'description' => 'KTV room for 4-8 persons. 3 hours consumable.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KTV Room (9-12 pax)', 'type' => 'ktv', 'capacity' => 12, 'price' => 4800, 'description' => 'KTV room for 9-12 persons. 3 hours consumable.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KTV Room (13-20 pax)', 'type' => 'ktv', 'capacity' => 20, 'price' => 5600, 'description' => 'KTV room for 13-20 persons. 3 hours consumable.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'KTV Room (20+ pax)', 'type' => 'ktv', 'capacity' => 30, 'price' => 6800, 'description' => 'KTV room for 20+ persons. 3 hours consumable.', 'status' => 'available', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
