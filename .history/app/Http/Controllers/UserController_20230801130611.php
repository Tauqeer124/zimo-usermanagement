<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\User;

use App\Models\Country;
use App\Models\UserDetail;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display the list of users using DataTables plugin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
  

    
    public function index(Request $request)
    {
        // $users = User::with('country', 'userDetail')->select('users.*')->get();
        // dd($users->country);
        $countries = Country::get();
        

        if ($request->ajax()) {
            $users = User::with('country', 'userDetail')->select('users.*');

            // Apply country filter if the country value is provided
            if ($request->has('filter_country')) {
                $filterCountry = $request->input('filter_country');
                $users->whereHas('country', function ($query) use ($filterCountry) {
                    $query->where('county', 'LIKE', '%' . $filterCountry . '%');
                });
            }

            // Apply date filter if provided
            if ($request->has('filter_date')) {
                $filterDate = $request->input('filter_date');
                $users->whereDate('users.created_at', '=', $filterDate);
            }

            return DataTables::of($users)
                ->addColumn('country_name', function ($user) {
                    return $user->country->country;
                })
                ->addColumn('gender', function ($user) {
                    return $user->userdetail->gender ;
                    
                })
                ->rawColumns(['country_name', 'gender'])
                ->make(true);
        }

        return view('users.index', compact('countries'));
    }

       /**
     * Store a new user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
{
    // Validate the input data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|max:20',
        'password' => 'required|string|min:6',
        'gender' => 'required|string|in:male,female', // Assuming gender can only be 'male' or 'female'
        'country' => 'required|string|max:255',
    ]);
    $ipAddress = $request->ip();

    // Create a new User instance and fill it with the input data
    $user = new User();
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->phone = $request->input('phone');
    $user->ip_address = $ipAddress;
    $user->password = Hash::make($request->input('password'));
    
    // Find the country by name or create it if it doesn't exist
    $country = Country::firstOrCreate(['country' => $request->input('country')]);
    $user->country_id = $country->id;

    $user->save();
    $user->country()->associate($country);

     // Create and associate user detail
     $user->userDetail()->create([
        'gender' => $request->input('gender'),
    ]);

    Session::flash('message', 'User created successfully.');
    return redirect()->route('user.index')->with('success', 'User created successfully.');
}
    /**
     * Display the details of a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        // Find the user with the given ID
        $user = User::with('userDetail', 'country')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Delete a user from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Find the user with the given ID
        $user = User::find($id);
    
        if (!$user) {
            return back()->with('error', 'User not found.');
        }
    
        // Delete the associated user detail if it exists
        if ($user->userDetail) {
            $user->userDetail->delete();
        }
    
        // Disassociate the country from the user, but do not delete the country record itself
        if ($user->country) {
            $user->country()->dissociate();
            $user->save();
        }
    
        // Delete the user record
        $user->delete();
    
        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Fetch user data to populate the edit form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        // Find the user with the given ID and return as a JSON response
        $user = User::with('userDetail', 'country')->find($id);
        $responseData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' =>  $user->country->country , // Use 'country_id' instead of 'country'
            'gender' =>  $user->userDetail->gender ,
        ];
    
        return response()->json($responseData);
        
    }

    /**
     * Update a user's information in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
{
    // Find the user with the given ID
    $user = User::find($id);
    if (!$user) {
        return response()->json(['error' => 'User not found.'], 404);
    }

    // Validate the input data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'required|string|max:20',
        'gender' => 'required|string|in:male,female', // Assuming gender can only be 'male' or 'female'
        'country' => 'required|string|max:255',
    ]);

    // Update the user's information with the input data
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->phone = $request->input('phone');
    $country = Country::firstOrCreate(['country' => $request->input('country')]);
    $user->country_id = $country->id;
    

    // Update the password if provided
    if ($request->input('password')) {
        $user->password = Hash::make($request->input('password'));
    }

    // Update user detail
    $user->userDetail()->update([
        'gender' => $request->input('gender'),
    ]);

    // Update country
    $country = Country::updateOrCreate(['country' => $request->input('country')]);
    $user->country()->associate($country);

    // Save the user
    $user->save();

    return response()->json(['success' => true]);
}
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    

    /**
     * Display user data for a specific country.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $country
     * @return \Illuminate\Contracts\View\View
     */
    public function showuserdata(Request $request, $country_id)
    {
        
        // Fetch users from the specific country
        $users= User::where('country_id', $country_id)->get();
        return view('users.data', compact('users', 'country_id'));
    }

    /**
     * Change the status of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
   
    public function updateStatus(Request $request, User $user)
    {
        $status = $request->input('status');
        
        // Map the 'active' status to 1 and 'block' status to 0
        $mappedStatus = $status === 'active' ? 1 : 0;
        
        // Update the user's status in the database
        $user->status = $mappedStatus;
        $user->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
    public function uploadimage(){
        return view('users.uploadimage');
    }
        
    public function exportExcel()
    {
        return Excel::download(new ExportUser, 'users.xlsx');
    }
}
