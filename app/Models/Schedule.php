<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'date',
    ];

    public function scheduleDetails(): HasMany
    {
        return $this->hasMany(ScheduleDetail::class, "schedule_id");
    }
}
