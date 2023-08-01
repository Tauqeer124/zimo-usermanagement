<?php

namespace App\Http\Controllers;
use App\Mail\WelcomeEmail;

use App\Models\User;
use App\Models\Country;
use App\Models\UserDetail;
use Illuminate\Http\Request;

use App\Jobs\WelcomeEmailJob;
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
        

        ], [
            'password.confirmed' => 'The password confirmation does not match the password.',
        ]);
        $ipAddress = $request->ip();
        // Create a new user in the database
        if($request->country){
            $country = Country::create([
                'country' => $request->country,
                
            ]);

        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'country_id' =>$country->id,
            'ip_address' => $ipAddress
        ]);
        if($request->gender){
            $userdetail = UserDetail::create([
                'gender' => $request->gender,
                'user_id' => $user->id
            ]);

        }
        

        // Send a welcome email to the newly registered user
        WelcomeEmailJob::dispatch($user)->delay(now()->addSeconds(10)); 
        
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
            WelcomeEmailJob::dispatch($user)->delay(now()->addSeconds(10)); 
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
