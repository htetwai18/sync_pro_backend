<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\PartInventoryResource;
use App\Models\PartInventory;
use Illuminate\Http\Request;

class PartInventoryController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = PartInventory::with(['part', 'location']);
        if ($request->filled('part_id')) {
            $query->where('part_id', $request->integer('part_id'));
        }
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->integer('location_id'));
        }
        $rows = $query->get();
        return $this->success(PartInventoryResource::collection($rows));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'location_id' => 'required|exists:inventory_locations,id',
            'quantity_on_hand' => 'required|integer',
        ]);
        $row = PartInventory::updateOrCreate(
            ['part_id' => $data['part_id'], 'location_id' => $data['location_id']],
            ['quantity_on_hand' => $data['quantity_on_hand']]
        );
        $row->load(['part','location']);
        return $this->success(new PartInventoryResource($row), 201);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'location_id' => 'required|exists:inventory_locations,id',
            'quantity_on_hand' => 'required|integer',
        ]);
        $row = PartInventory::where('part_id', $data['part_id'])
            ->where('location_id', $data['location_id'])
            ->firstOrFail();
        $row->update(['quantity_on_hand' => $data['quantity_on_hand']]);
        $row->load(['part','location']);
        return $this->success(new PartInventoryResource($row));
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'location_id' => 'required|exists:inventory_locations,id',
        ]);
        PartInventory::where('part_id', $data['part_id'])
            ->where('location_id', $data['location_id'])
            ->delete();
        return $this->success(null, 204);
    }
}


