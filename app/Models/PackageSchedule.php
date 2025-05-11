<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageSchedule extends Model
{
    protected $fillable = [
        "package_transaction_id",
        "schedule_detail_id"
    ];

    public function packageTransaction(): BelongsTo
    {
        return $this->belongsTo(PackageTransaction::class, 'package_transaction_id', 'id');
    }

    public function scheduleDetail(): BelongsTo
    {
        return $this->belongsTo(ScheduleDetail::class, 'schedule_detail_id');
    }
}
