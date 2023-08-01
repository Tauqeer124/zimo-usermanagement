<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Mail\WelcomeEmail;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function register()
    {
        $countries = Country::all();
        return view('auth.register' ,compact('countries'));
    }

    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registeruser(Request $request)
    {
        // Validate the registration form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
            'country_id' => 'required|exists:countries,id',

        ], [
            'password.confirmed' => 'The password confirmation does not match the password.',
        ]);
       
        // Create a new user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);
        if($request->gender){
            $userdetail = UserDetail::create([
                'gender' => $request->gender,
                'user_id' => $user->id
            ]);

        }
        if($request->country){
            $country = UserDetail::create([
                'gender' => $request->co,
                'user_id' => $user->id
            ]);

        }

        // Send a welcome email to the newly registered user
        Mail::to($user->email)->send(new WelcomeEmail($user));
        $request->session()->flash('success', 'You have registered successfully!');
        return redirect()->route('login');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginuser(Request $request)
    {
        // Validate the login form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get the user by email
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Generate a token
            $token = $user->createToken('Personal Access Token')->accessToken;
            // Send a welcome email to the logged-in user
            Mail::to($user->email)->send(new WelcomeEmail($user));
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        } else {
            return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
        }
    }

    /**
     * Logout  the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('message', 'You have been logged out.');
    }
}
