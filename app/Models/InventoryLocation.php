<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InventoryLocation extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_name',
        'contact_phone',
        'is_active',
    ];

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(Part::class, 'part_inventory')
            ->withPivot('quantity_on_hand')
            ->withTimestamps();
    }
}


