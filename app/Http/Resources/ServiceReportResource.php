<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'submitted_by_id' => $this->submitted_by_id,
            'reviewed_by_id' => $this->reviewed_by_id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'attachment_url' => $this->attachment_url,
            'submitted_date' => $this->submitted_date,
            'approved_date' => $this->approved_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'task' => new TaskResource($this->whenLoaded('task')),
            'submitted_by' => new UserResource($this->whenLoaded('submittedBy')),
            'reviewed_by' => new UserResource($this->whenLoaded('reviewedBy')),
        ];
    }
}


