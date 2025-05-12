<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleTemplateDetail extends Model
{
    protected $fillable = [
        "schedule_template_id",
        "class_id",
        "quota",
        "schedule_time",
    ];

    public function scheduleTemplate(): BelongsTo
    {
        return $this->belongsTo(ScheduleTemplate::class, 'schedule_template_id');
    }

    public function classes(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'schedule_template_id');
    }
}
