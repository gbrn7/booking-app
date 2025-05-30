<?php

namespace Database\Seeders;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::insert(
            [
                "class_type_id" => 1,
                "number_of_session" => 2,
                "price" => 200000,
                "is_trial" => false,
                "duration" => 1,
                "duration_unit" => 'week',
            ],
            [
                "class_type_id" => 1,
                "number_of_session" => 5,
                "price" => 300000,
                "is_trial" => true,
                "duration" => 3,
                "duration_unit" => 'week',
            ],
        );
    }
}
