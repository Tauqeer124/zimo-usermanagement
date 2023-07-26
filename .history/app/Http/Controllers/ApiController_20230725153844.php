<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function loginapi(Request $request)
        {
            
            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
    
            $user = User::where('email', $request->email)->first();
            // dd($user);
    
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            $token = $user->createToken('user_login_token')->plainTextToken;
            Mail::to($user->email)->send(new WelcomeEmail($user)); // Send welcome email

    
            return response()->json(['token' => $token], 200);
        }

}
