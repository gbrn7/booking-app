<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageTransaction extends Model
{
    protected $fillable = [
        "package_id",
        "name",
        "phone_num",
        "email",
        "class_name",
        "transaction_code",
        "payment_status",
        "instructure_name",
        "number_of_session",
        "number_of_session_left",
        "group_class_type",
        "class_type",
        "is_trial",
        "redeem_code",
        "valid_until",
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
}
