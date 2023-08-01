<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;

use App\Models\Country;
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
    public function index2(Request $request)
    {
        // Get unique countries to use as filters
        
        $countries = Country::get();
        // dd($countries);
    
        // if ($request->ajax()) {
            $data = User::select('users.*', 'countries.name as country')
                ->leftJoin('countries', 'users.country_id', '=', 'countries.id');
    
            // Apply search filter on country name
            if ($request->has('filter_country')) {
                $filterCountry = $request->input('filter_country');
                $query->where('name', 'like', '%' . $filterCountry . '%');            }
    
            // Apply date filter if provided
            if ($request->has('filter_date')) {
                $filterDate = $request->input('filter_date');
                $data->whereDate('users.created_at', '=', $filterDate);
                
            }
    
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // ... your action column code ...
                })
                ->make(true);
        // }
    
        return view('users.index', compact('countries'));
    }
    
    
 public function index(Request $request)
    {
        // Get unique countries to use as filters
        
        $countries = Country::get();
        
    
        if ($request->ajax()) {
            $data = User::select('users.*', 'countries.name as country_')
                ->leftJoin('countries', 'users.country_id', '=', 'countries.id');
                // dd($data);
    
            // Apply search filter on country name
            if ($request->has('filter_country')) {
                $filterCountry = $request->input('filter_country');
                
                $countries->where('country', 'LIKE', '%' . $filterCountry . '%');
            }
    
            // Apply date filter if provided
            if ($request->has('filter_date')) {
                $filterDate = $request->input('filter_date');
                $data->whereDate('users.created_at', '=', $filterDate);
            }
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // ... your action column code ...
                })
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
        // Create a new User instance and fill it with the input data
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->country_id = $request->input('country_id');
        $user->password = Hash::make($request->input('password'));
    
        $user->save();
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
        $user = User::findOrFail($id);
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
        User::find($id)->delete();
        return back();
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
        $user = User::find($id);
        return response()->json($user);
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
        // Update the user's information with the input data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->country_id = $request->input('country_id');
        
        // Update the password if provided
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
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

        // $users = Country::where('name', $name)->get();
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
        
    public function exportExcel()
    {
        return Excel::download(new ExportUser, 'users.xlsx');
    }
}
