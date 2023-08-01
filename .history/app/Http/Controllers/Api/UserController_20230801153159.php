<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index2(Request $request)
    
    {
        
        
        $users = User::with('country', 'userDetail')->orderby('id','desc')->get();
        
        return UserResource::collection($users);
    }
    public function index2(Request $request){
    // Get the optional parameters from the request
    $countryId = $request->input('country_id');
    $sortDirection = $request->input('sort_direction', 'asc'); // Default to ascending if not provided
    $sortColumn = $request->input('sort_column', 'id'); // Default to sorting by 'id' if not provided

    // Query users with country and userDetail relationships
    $query = User::with('country', 'userDetail');

    // Apply filter by country if country_id is provided
    if ($countryId) {
        $query->whereHas('country', function ($q) use ($countryId) {
            $q->where('id', $countryId);
        });
    }

    // Apply sorting
    $query->orderBy($sortColumn, $sortDirection);

    // Fetch the results
    $users = $query->get();

    // Return the results as a UserResource collection
    return UserResource::collection($users);
}
    /**
 * Display the details of a specific user with relationships.
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function show(Request $request,$id)

{
    
    // Find the user with the given ID and load relationships
    $user = User::with('country', 'userDetail')->findOrFail($id);
    return new UserResource($user);
}

/**
 * Store a new user in the database with relationships.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string',
        
        'password' => 'required|min:6',
        // Add other validation rules as needed
    ]);
    $ipAddress = $request->ip();
    // Create the user
    $country = Country::firstOrCreate(['country' => $request->input('country')]);
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'country_id' => $country->id,
        'ip_address' => $ipAddress,
        'password' => Hash::make($request->password),
        // Add other fields as needed
    ]);

    // Create user details relationship
    if ($request->has('gender')) {
        $user->userDetail()->create([
            'gender' => $request->gender,
            // Add other user detail fields as needed
        ]);
    }

    // Return the new user resource with relationships
    return new UserResource($user->load('country', 'userDetail'));
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
        
        // Add other validation rules as needed
    ]);

    // Update the user's information
    $country = Country::firstOrCreate(['country' => $request->input('country')]);
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'country_id' => $country->id,
        'status' => $request->status,
        'password' => Hash::make($request->password),
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
