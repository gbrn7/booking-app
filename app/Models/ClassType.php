<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'group_class_type_id',
        'name',
    ];


    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'class_type_id', 'id');
    }

    public function groupClassType(): BelongsTo
    {
        return $this->belongsTo(GroupClassType::class, 'group_class_type_id', 'id');
    }
}
