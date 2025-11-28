<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\InventoryLocationResource;
use App\Models\InventoryLocation;
use Illuminate\Http\Request;

class InventoryLocationController extends BaseApiController
{
    public function index()
    {
        $rows = InventoryLocation::query()->get();
        return $this->success(InventoryLocationResource::collection($rows));
    }

    public function show(InventoryLocation $inventory_location)
    {
        return $this->success(new InventoryLocationResource($inventory_location));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:inventory_locations,code',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        $row = InventoryLocation::create($data);
        return $this->success(new InventoryLocationResource($row), 201);
    }

    public function update(Request $request, InventoryLocation $inventory_location)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:inventory_locations,code,'.$inventory_location->id,
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);
        $inventory_location->update($data);
        return $this->success(new InventoryLocationResource($inventory_location));
    }

    public function destroy(InventoryLocation $inventory_location)
    {
        $inventory_location->delete();
        return $this->success(null, 204);
    }
}


