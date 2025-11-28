<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Part extends Model
{
    protected $fillable = [
        'name',
        'number',
        'manufacturer',
        'unit_price',
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_parts')
            ->withPivot('quantity_used')
            ->withTimestamps();
    }
}


