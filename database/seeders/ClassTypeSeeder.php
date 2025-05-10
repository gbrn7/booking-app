<?php

namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassType::insert([
            [
                'name' => "Pilates",
            ],
            [
                'name' => "Aerial",
            ],
            [
                'name' => "Yoga",
            ],
        ]);
    }
}
