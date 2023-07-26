<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register()
    {
        // dd("k");
        return view('auth.register');
    }

    public function registeruser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
            'country' => 'required|string',
        ], [
            'password.confirmed' => 'The password confirmation does not match the password.',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'country' => $request->country,
        ]);

        Mail::to($user->email)->send(new WelcomeEmail($user)); // Send welcome email
        $request->session()->flash('success', 'You have registered successfully!');

        // Redirect back to the registration page
        return redirect()->back();
    }
    public function login(){
        return view('auth.login');
    }
    public function loginuser(Request $request)
    {
        // Validate the form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Get the user by email
        $user = User::where('email', $request->email)->first();
    
        // Check if the user exists and the password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Generate a token and assign it to the authenticated user
            $token = $user->createToken('Personal Access Token')->accessToken;
            // Redirect the user to the desired location with the token
            return redirect()->intended('/dashboard')->with('success', 'Login successful!')->header('Authorization', 'Bearer ' . $token);
        } 
        else {
                // Invalid credentials, redirect back with an error message
                return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
        }
    }
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
    
            return response()->json(['token' => $token], 200);
        }
    
}
