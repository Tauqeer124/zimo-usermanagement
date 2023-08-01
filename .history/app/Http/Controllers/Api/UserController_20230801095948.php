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

}