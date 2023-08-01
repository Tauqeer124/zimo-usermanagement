<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ...

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::with('country', 'userDetail')->get();
        return UserResource::collection($users);
    }
    /**
 * Display the details of a specific user with relationships.
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function show($id)
{
    // Find the user with the given ID and load relationships
    $user = User::with('country', 'userDetail')->findOrFail($id);
    return new UserResource($user);
}


/**
 * Update a user's information in the database with relationships.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function update(Request $request, $id)
{
    // Find the user with the given ID
    $user = User::findOrFail($id);

    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'phone' => 'required|string',
        'country_id' => 'required|exists:countries,id',
        // Add other validation rules as needed
    ]);

    // Update the user's information
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'country_id' => $request->country_id,
        // Add other fields as needed
    ]);

    // Update user details relationship
    if ($request->has('gender')) {
        if ($user->userDetail) {
            $user->userDetail->update([
                'gender' => $request->gender,
                // Add other user detail fields as needed
            ]);
        } else {
            $user->userDetail()->create([
                'gender' => $request->gender,
                // Add other user detail fields as needed
            ]);
        }
    } else {
        // If gender is not provided, delete user details relationship if it exists
        if ($user->userDetail) {
            $user->userDetail->delete();
        }
    }

    // Return the updated user resource with relationships
    return new UserResource($user->load('country', 'userDetail'));
}
/**
 * Delete a user from the database.
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function destroy($id)
{
    // Find the user with the given ID and delete it
    $user = User::findOrFail($id);
    $user->delete();

    // Return a success response
    return response()->json(['message' => 'User deleted successfully'], 200);
}

}