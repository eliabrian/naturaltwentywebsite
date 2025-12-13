<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Room::create([
            'name' => 'VIP Room',
            'slug' => 'vip',
            'deposit' => 100000,
            'base_cost' => 700000,
            'person_cost' => 0,
        ]);

        Room::create([
            'name' => 'D&D Room',
            'slug' => 'dnd',
            'deposit' => 85000,
            'base_cost' => 0,
            'person_cost' => 85000,
        ]);
    }
}
