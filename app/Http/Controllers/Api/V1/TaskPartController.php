<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Task;
use App\Models\Part;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;

class TaskPartController extends BaseApiController
{
    public function index(Task $task)
    {
        $task->load(['parts']);
        return $this->success([
            'task_id' => $task->id,
            'parts' => $task->parts->map(function ($p) {
                return [
                    'part_id' => $p->id,
                    'name' => $p->name,
                    'quantity_used' => $p->pivot->quantity_used,
                ];
            }),
        ]);
    }

    public function store(Request $request, Task $task)
    {
        $data = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'quantity_used' => 'required|integer|min:0',
        ]);
        $task->parts()->syncWithoutDetaching([
            $data['part_id'] => ['quantity_used' => $data['quantity_used']]
        ]);
        $task->load(['parts']);
        return $this->success(new TaskResource($task->load([
            'customer','createdBy','assignedTo','asset','asset.building','asset.building.user','parts','serviceReport'
        ])));
    }

    public function update(Request $request, Task $task, Part $part)
    {
        $data = $request->validate([
            'quantity_used' => 'required|integer|min:0',
        ]);
        $task->parts()->updateExistingPivot($part->id, ['quantity_used' => $data['quantity_used']]);
        return $this->success([
            'task_id' => $task->id,
            'part_id' => $part->id,
            'quantity_used' => $data['quantity_used'],
        ]);
    }

    public function destroy(Task $task, Part $part)
    {
        $task->parts()->detach($part->id);
        return $this->success(null, 204);
    }
}


