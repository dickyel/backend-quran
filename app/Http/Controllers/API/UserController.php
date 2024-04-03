<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str; // Import Str class untuk membuat slug

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::all();
    
        return response()->json(['data' => UserResource::collection($users), 'message' => 'Users retrieved successfully'], 200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,member' // Menambahkan validasi untuk role
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'message' => $validator->errors()], 422);
        }
    
        // Buat pengguna baru
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']), // Hashing password
            'role' => $input['role']
        ]);
    
        return response()->json(['data' => new UserResource($user), 'message' => 'User created successfully'], 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */

    public function edit(User $user): JsonResponse
    {
        return response()->json(['data' => new UserResource($user), 'message' => 'Edit user'], 200);
    }
    
    public function update(Request $request, User $user): JsonResponse
    {
        $input = $request->all();
    
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|min:6',
            'role' => 'required|in:admin,member'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error.', 'message' => $validator->errors()], 422);
        }
    
        // Hashing password
        $input['password'] = bcrypt($input['password']);
    
        // Update user data
        $user->update($input);
    
        return response()->json(['data' => new UserResource($user), 'message' => 'User updated successfully'], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

}
