<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        "class_id",
        "number_of_session",
        "price",
        "is_trial",
        "valid_until",
    ];

    public function classes(): BelongsTo
    {
        return $this->belongsTo('classes', 'class_id');
    }

    public function packageTransaction(): HasMany
    {
        return $this->hasMany(PackageTransaction::class, 'package_id');
    }
}
