<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends BaseApiController
{
    public function index()
    {
        $rows = Invoice::with(['user','items'])->get();
        return $this->success(InvoiceResource::collection($rows));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['user','items']);
        return $this->success(new InvoiceResource($invoice));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,void',
        ]);
        $row = Invoice::create($data);
        $row->load(['user','items']);
        return $this->success(new InvoiceResource($row), 201);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'invoice_date' => 'sometimes|date',
            'due_date' => 'sometimes|date',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:draft,sent,paid,void',
        ]);
        $invoice->update($data);
        $invoice->load(['user','items']);
        return $this->success(new InvoiceResource($invoice));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return $this->success(null, 204);
    }
}


