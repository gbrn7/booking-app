<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleDetail extends Model
{
    protected $fillable = [
        "schedule_id",
        "class_id",
        "quota",
        "schedule_time",
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ScheduleDetail::class, 'schedule_id');
    }
}
