<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "schedule_id",
        "class_id",
        "quota",
        "schedule_time",
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    public function getFormattedTimeAttribute(): string
    {
        return Carbon::parse($this->schedule_time)->format('H:i');
    }

    public function classes(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    protected function casts(): array
    {
        return [
            'schedule_time' => 'datetime:H:i',
        ];
    }
}
