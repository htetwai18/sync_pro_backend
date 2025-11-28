<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends BaseApiController
{
    private array $with = [
        'customer','createdBy','assignedTo','asset','asset.building','asset.building.user','parts','serviceReport'
    ];

    public function index()
    {
        $rows = Task::with($this->with)->get();
        return $this->success(TaskResource::collection($rows));
    }

    public function show(Task $task)
    {
        $task->load($this->with);
        return $this->success(new TaskResource($task));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'asset_id' => 'required|exists:assets,id',
            'created_by_id' => 'required|exists:users,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,assigned,in_progress,completed,rejected',
            'priority' => 'required|in:low,medium,high,urgent',
            'type' => 'required|string|max:100',
            'notes' => 'nullable|string',
            'preferred_date' => 'nullable|date',
            'preferred_time_slot' => 'nullable|string|max:50',
            'scheduling_details' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'request_date' => 'nullable|date',
            'assigned_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
        ]);
        $row = Task::create($data);
        $row->load($this->with);
        return $this->success(new TaskResource($row), 201);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'customer_id' => 'sometimes|exists:users,id',
            'asset_id' => 'sometimes|exists:assets,id',
            'created_by_id' => 'sometimes|exists:users,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,assigned,in_progress,completed,rejected',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'type' => 'sometimes|string|max:100',
            'notes' => 'nullable|string',
            'preferred_date' => 'nullable|date',
            'preferred_time_slot' => 'nullable|string|max:50',
            'scheduling_details' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'request_date' => 'nullable|date',
            'assigned_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
        ]);
        $task->update($data);
        $task->load($this->with);
        return $this->success(new TaskResource($task));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return $this->success(null, 204);
    }
}


