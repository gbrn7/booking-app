<?php

namespace Database\Seeders;

use App\Models\ScheduleDetail;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScheduleDetail::insert([
            [
                'schedule_id' => 1,
                'class_id' => 1,
                'quota' => 10,
                'schedule_time' => Carbon::now()->format('H:i'),
            ],
            [
                'schedule_id' => 1,
                'class_id' => 1,
                'quota' => 10,
                'schedule_time' => Carbon::now()->addHour(1)->format('H:i'),
            ],
            [
                'schedule_id' => 1,
                'class_id' => 2,
                'quota' => 10,
                'schedule_time' => Carbon::now()->subHour(2)->format('H:i'),
            ],
        ]);
    }
}
