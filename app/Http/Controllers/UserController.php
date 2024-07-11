<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|in:Admin,Supervisor,Agent',
            'email' => 'required|string|email|max:255|unique:users',
            'location_latitude' => 'nullable|numeric|between:-90,90',
            'location_longitude' => 'nullable|numeric|between:-180,180',
            'date_of_birth' => 'required|date',
            'timezone' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('User creation validation failed', $validator->errors()->toArray());
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role' => $request->role,
            'email' => $request->email,
            'location_latitude' => $request->location_latitude,
            'location_longitude' => $request->location_longitude,
            'date_of_birth' => $request->date_of_birth,
            'timezone' => $request->timezone,
            'password' => Hash::make($request->password),
        ]);

        Log::info('User created successfully', ['user_id' => $user->id]);
        return response()->json($user, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => 'required|in:Admin,Supervisor,Agent',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'location_latitude' => 'nullable|numeric|between:-90,90',
            'location_longitude' => 'nullable|numeric|between:-180,180',
            'date_of_birth' => 'required|date',
            'timezone' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('User update validation failed', $validator->errors()->toArray());
            return response()->json($validator->errors(), 400);
        }

        $user->update($request->only([
            'first_name',
            'last_name',
            'role',
            'email',
            'location_latitude',
            'location_longitude',
            'date_of_birth',
            'timezone',
        ]));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        Log::info('User updated successfully', ['user_id' => $user->id]);
        return response()->json($user, 200);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        Log::info('User deleted successfully', ['user_id' => $user->id]);
        return response()->json(null, 204);
    }
}
