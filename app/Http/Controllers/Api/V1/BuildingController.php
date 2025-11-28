<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends BaseApiController
{
    public function index()
    {
        $rows = Building::with(['user', 'assets'])->get();
        return $this->success(BuildingResource::collection($rows));
    }

    public function show(Building $building)
    {
        $building->load(['user', 'assets']);
        return $this->success(new BuildingResource($building));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:512',
            'room_number' => 'nullable|string|max:50',
        ]);
        $row = Building::create($data);
        $row->load(['user', 'assets']);
        return $this->success(new BuildingResource($row), 201);
    }

    public function update(Request $request, Building $building)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:512',
            'room_number' => 'nullable|string|max:50',
        ]);
        $building->update($data);
        $building->load(['user', 'assets']);
        return $this->success(new BuildingResource($building));
    }

    public function destroy(Building $building)
    {
        $building->delete();
        return $this->success(null, 204);
    }
}


