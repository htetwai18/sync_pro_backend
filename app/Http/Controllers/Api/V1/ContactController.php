<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends BaseApiController
{
    public function index()
    {
        $rows = Contact::with(['user'])->get();
        return $this->success(ContactResource::collection($rows));
    }

    public function show(Contact $contact)
    {
        $contact->load(['user']);
        return $this->success(new ContactResource($contact));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:100',
        ]);
        $row = Contact::create($data);
        $row->load(['user']);
        return $this->success(new ContactResource($row), 201);
    }

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:100',
        ]);
        $contact->update($data);
        $contact->load(['user']);
        return $this->success(new ContactResource($contact));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return $this->success(null, 204);
    }
}


