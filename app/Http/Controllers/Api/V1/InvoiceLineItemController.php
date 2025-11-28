<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\InvoiceLineItemResource;
use App\Models\InvoiceLineItem;
use Illuminate\Http\Request;

class InvoiceLineItemController extends BaseApiController
{
    public function index()
    {
        $rows = InvoiceLineItem::query()->get();
        return $this->success(InvoiceLineItemResource::collection($rows));
    }

    public function show(InvoiceLineItem $invoice_line_item)
    {
        return $this->success(new InvoiceLineItemResource($invoice_line_item));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);
        $row = InvoiceLineItem::create($data);
        return $this->success(new InvoiceLineItemResource($row), 201);
    }

    public function update(Request $request, InvoiceLineItem $invoice_line_item)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'quantity' => 'sometimes|integer|min:1',
            'unit_price' => 'sometimes|numeric|min:0',
        ]);
        $invoice_line_item->update($data);
        return $this->success(new InvoiceLineItemResource($invoice_line_item));
    }

    public function destroy(InvoiceLineItem $invoice_line_item)
    {
        $invoice_line_item->delete();
        return $this->success(null, 204);
    }
}


