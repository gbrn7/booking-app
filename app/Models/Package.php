<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "class_type_id",
        "number_of_session",
        "price",
        "is_trial",
        "duration",
        "duration_unit",
    ];

    public function classType(): BelongsTo
    {
        return $this->belongsTo(ClassType::class, 'class_type_id');
    }

    public function packageTransaction(): HasMany
    {
        return $this->hasMany(PackageTransaction::class, 'package_id');
    }

    public function localDurationUnit(): string
    {
        switch ($this->duration_unit) {
            case 'day':
                return "Hari";
                break;
            case 'week':
                return "Minggu";
                break;
            case 'month':
                return "Bulan";
                break;
            case 'year':
                return "Tahun";
                break;
            default:
                return "Minggu";
                break;
        }
    }
}
