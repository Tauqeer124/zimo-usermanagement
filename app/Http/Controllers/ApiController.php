<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    public function login(Request $request)
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
