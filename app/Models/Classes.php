<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classes extends Model
{
    protected $fillable = [
        'group_class_type_id',
        'class_type_id',
        'name',
        'instructure_name',
    ];


    public function classType(): BelongsTo
    {
        return $this->belongsTo(ClassType::class, 'class_type_id', 'id');
    }

    public function groupClassType(): BelongsTo
    {
        return $this->belongsTo(GroupClassType::class, 'group_class_type', 'id');
    }
}
