<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classes::insert([
            [
                'group_class_type_id' => 1,
                'class_type_id' => 1,
                'name' => "Pilates Reformer and/or Tower",
                'instructure_name' => "Nana",
            ],
            [
                'group_class_type_id' => 2,
                'class_type_id' => 1,
                'name' => "Aerial Hammock Privat",
                'instructure_name' => "Firli",
            ],
        ]);
    }
}
