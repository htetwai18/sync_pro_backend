<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role ?? null,
            'phone' => $this->phone ?? null,
            'specialization' => $this->specialization ?? null,
            'department' => $this->department ?? null,
            'hire_date' => $this->hire_date ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'buildings' => BuildingResource::collection($this->whenLoaded('buildings')),
        ];
    }
}


