<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassType extends Model
{
    protected $fillable = [
        'name',
    ];


    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'class_type_id', 'id');
    }
}
