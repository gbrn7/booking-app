<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'date',
    ];

    public function scheduleDetails(): HasMany
    {
        return $this->hasMany(ScheduleDetail::class, "schedule_id");
    }

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)
            ->locale('id')
            ->translatedFormat('l, d F Y');
    }
}
