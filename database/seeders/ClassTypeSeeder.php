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
                'group_class_type_id' => 1,
            ],
            [
                'name' => "Pilates Privat",
                'group_class_type_id' => 2,
            ],
            [
                'name' => "Aerial",
                'group_class_type_id' => 1,
            ],
            [
                'name' => "Aerial Privat",
                'group_class_type_id' => 2,
            ],
            [
                'name' => "Yoga",
                'group_class_type_id' => 1,
            ],
            [
                'name' => "Yoga Privat",
                'group_class_type_id' => 2,
            ],
        ]);
    }
}
