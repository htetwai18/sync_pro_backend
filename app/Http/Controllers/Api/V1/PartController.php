<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\PartResource;
use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends BaseApiController
{
    public function index()
    {
        $rows = Part::query()->get();
        return $this->success(PartResource::collection($rows));
    }

    public function show(Part $part)
    {
        return $this->success(new PartResource($part));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:100|unique:parts,number',
            'manufacturer' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0',
        ]);
        $row = Part::create($data);
        return $this->success(new PartResource($row), 201);
    }

    public function update(Request $request, Part $part)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'number' => 'sometimes|string|max:100|unique:parts,number,'.$part->id,
            'manufacturer' => 'nullable|string|max:255',
            'unit_price' => 'sometimes|numeric|min:0',
        ]);
        $part->update($data);
        return $this->success(new PartResource($part));
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return $this->success(null, 204);
    }
}


