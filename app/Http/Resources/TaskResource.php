<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'asset_id' => $this->asset_id,
            'created_by_id' => $this->created_by_id,
            'assigned_to_id' => $this->assigned_to_id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'type' => $this->type,
            'notes' => $this->notes,
            'preferred_date' => $this->preferred_date,
            'preferred_time_slot' => $this->preferred_time_slot,
            'scheduling_details' => $this->scheduling_details,
            'special_instructions' => $this->special_instructions,
            'request_date' => $this->request_date,
            'assigned_date' => $this->assigned_date,
            'completed_date' => $this->completed_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => new UserResource($this->whenLoaded('customer')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'asset' => new AssetResource($this->whenLoaded('asset')),
            // depth-2 nested via asset.building and building.user (owner)
            'asset_building' => new BuildingResource($this->whenLoaded('asset.building')),
            'asset_building_user' => new UserResource($this->whenLoaded('asset.building.user')),
            'parts' => PartResource::collection($this->whenLoaded('parts'))->additional([]),
            'pivot' => $this->whenPivotLoaded('task_parts', function () {
                return ['quantity_used' => $this->pivot->quantity_used];
            }),
            'service_report' => new ServiceReportResource($this->whenLoaded('serviceReport')),
        ];
    }
}


