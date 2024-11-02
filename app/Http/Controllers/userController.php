<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all users
        $users = User::with('student')->get();

        // Return the users as a JSON response
        return UserResource::collection($users);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required',
                'phone' => 'string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'profile' => 'sometimes::max:255'
            ]);


            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile' => $request->profile,
                'role_id' => 2, // Default role is 'user' if not provided
                'password' => bcrypt($request->password), // Encrypt the password
            ]);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json([
                'message' => 'An error occurred during user creation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('student')->findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Validate the request data
            $credential = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
                'phone' => 'sometimes|required|string',
                'profile' => 'sometimes|max:255', // File validation for profile image
                'role_id' => 'sometimes|integer', // Optional, if you allow updating role
                'password' => 'sometimes|nullable|string|min:8', // Optional if password update is allowed
            ]);

            // Handle profile image upload
            $profilePath = $user->profile; // Start with the existing profile path

            if ($request->hasFile('profile')) {
                // Check if the user's current profile is not null and file exists
                if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                    // Delete the old profile image from storage
                    Storage::disk('public')->delete($user->profile);
                }
                // Store the new profile image
                $profilePath = $request->file('profile')->store('users', 'public');
            }

            // Update user data
            $user->update(array_filter([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'profile' => $profilePath,
                'role_id' => $request->role_id,
                'password' => $request->password ? bcrypt($request->password) : null,
            ]));

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'user' => $user,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['message' => 'Validation Failed', 'errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where user is not found
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json([
                'message' => 'An error occurred during user update',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Delete the user
            $user->delete();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle case where user is not found
            return response()->json([
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json([
                'message' => 'An error occurred while trying to delete the user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
