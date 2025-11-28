<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    public function index()
    {
        $users = User::with(['contacts', 'buildings'])->get();
        return $this->success(UserResource::collection($users));
    }

    public function show(User $user)
    {
        $user->load(['contacts', 'buildings']);
        return $this->success(new UserResource($user));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:customer,engineer,admin',
            'phone' => 'nullable|string|max:50',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->load(['contacts', 'buildings']);
        return $this->success(new UserResource($user), 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:customer,engineer,admin',
            'phone' => 'nullable|string|max:50',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
        ]);
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
        $user->load(['contacts', 'buildings']);
        return $this->success(new UserResource($user));
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->success(null, 204);
    }
}


