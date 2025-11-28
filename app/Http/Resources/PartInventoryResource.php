<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartInventoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'part_id' => $this->part_id,
            'location_id' => $this->location_id,
            'quantity_on_hand' => $this->quantity_on_hand,
            'part' => new PartResource($this->whenLoaded('part')),
            'location' => new InventoryLocationResource($this->whenLoaded('location')),
        ];
    }
}


