<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackageTransaction extends Model
{
    protected $fillable = [
        "package_id",
        "class_type_name",
        "customer_name",
        "transaction_code",
        "price",
        "phone_num",
        "email",
        "transaction_code",
        "payment_status",
        "number_of_session",
        "number_of_session_left",
        "group_class_type",
        "is_trial",
        "redeem_code",
        "valid_until",
        "duration",
        "duration_unit",
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function packageSchedules(): HasMany
    {
        return $this->hasMany(PackageSchedule::class, 'package_transaction_id', 'id');
    }
}
