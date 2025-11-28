<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    protected $fillable = [
        'customer_id',
        'asset_id',
        'created_by_id',
        'assigned_to_id',
        'title',
        'description',
        'status',
        'priority',
        'type',
        'notes',
        'preferred_date',
        'preferred_time_slot',
        'scheduling_details',
        'special_instructions',
        'request_date',
        'assigned_date',
        'completed_date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(Part::class, 'task_parts')
            ->withPivot('quantity_used')
            ->withTimestamps();
    }

    public function serviceReport(): HasOne
    {
        return $this->hasOne(ServiceReport::class);
    }
}


