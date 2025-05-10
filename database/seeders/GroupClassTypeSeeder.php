<?php

namespace Database\Seeders;

use App\Models\GroupClassType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupClassTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GroupClassType::insert([
            [
                'name' => 'group',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'private',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
