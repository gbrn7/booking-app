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
                'class_type_id' => 1,
                'name' => "Pilates Reformer and/or Tower",
                'instructure_name' => "Nana",
            ],
            [
                'class_type_id' => 2,
                'name' => "Aerial Hammock Privat",
                'instructure_name' => "Firli",
            ],
        ]);
    }
}
