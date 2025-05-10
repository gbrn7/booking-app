<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleTemplate extends Model
{
    protected $fillable = [
        "name"
    ];

    public function scheduleTemplateDetails(): HasMany
    {
        return $this->hasMany(ScheduleTemplateDetail::class, 'schedule_template_id', 'id');
    }
}
