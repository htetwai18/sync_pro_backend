<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'building_id' => $this->building_id,
            'name' => $this->name,
            'manufacturer' => $this->manufacturer,
            'model' => $this->model,
            'installation_date' => $this->installation_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'building' => new BuildingResource($this->whenLoaded('building')),
            'building_user' => new UserResource($this->whenLoaded('building.user')),
        ];
    }
}


