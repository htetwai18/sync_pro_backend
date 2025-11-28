<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartInventory extends Model
{
    protected $table = 'part_inventory';

    public $incrementing = false; // composite key logical handling
    protected $primaryKey = null;

    protected $fillable = [
        'part_id',
        'location_id',
        'quantity_on_hand',
    ];

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(InventoryLocation::class, 'location_id');
    }
}


