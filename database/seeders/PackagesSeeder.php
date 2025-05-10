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
                "class_id" => 1,
                "number_of_session" => 1,
                "price" => 150000,
                "is_trial" => true,
                "valid_until" => Carbon::now()->addWeek(2),
            ],
        );
    }
}
