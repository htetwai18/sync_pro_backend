<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends BaseApiController
{
    public function index()
    {
        $rows = Asset::with(['building', 'building.user'])->get();
        return $this->success(AssetResource::collection($rows));
    }

    public function show(Asset $asset)
    {
        $asset->load(['building', 'building.user']);
        return $this->success(new AssetResource($asset));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'name' => 'required|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'installation_date' => 'required|date',
        ]);
        $row = Asset::create($data);
        $row->load(['building', 'building.user']);
        return $this->success(new AssetResource($row), 201);
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'building_id' => 'sometimes|exists:buildings,id',
            'name' => 'sometimes|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'installation_date' => 'sometimes|date',
        ]);
        $asset->update($data);
        $asset->load(['building', 'building.user']);
        return $this->success(new AssetResource($asset));
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return $this->success(null, 204);
    }
}


