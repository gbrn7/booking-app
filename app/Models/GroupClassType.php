<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClassType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'class_type_id', 'id');
    }
}
