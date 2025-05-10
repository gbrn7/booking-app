<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageSchedule extends Model
{
    protected $fillable = [
        "package_transaction_id",
        "day",
        "schedule_time",
    ];
}
