<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
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
  

public function index(Request $request)
{
    
    public function index(Request $request)
    {
        $countries = Country::get();

        if ($request->ajax()) {
            $users = User::with('country', 'userDetail')->select('users.*');

            // Apply country filter if the country value is provided
            if ($request->has('filter_country')) {
                $filterCountry = $request->input('filter_country');
                $users->whereHas('country', function ($query) use ($filterCountry) {
                    $query->where('name', 'LIKE', '%' . $filterCountry . '%');
                });
            }

            // Apply date filter if provided
            if ($request->has('filter_date')) {
                $filterDate = $request->input('filter_date');
                $users->whereDate('users.created_at', '=', $filterDate);
            }

            return DataTables::of($users)
                ->addColumn('country_name', function ($user) {
                    return $user->country->name;
                })
                ->addColumn('userDetail_gender', function ($user) {
                    return $user->userDetail->gender;
                })
                ->rawColumns(['country_name', 'userDetail_gender'])
                ->make(true);
        }

        return view('users.index', compact('countries'));
    }
}
Step 2: Create the index.blade.php View (resources/views/users/index.blade.php):

html
Copy code
@extends('layouts.app')

@section('content')
    <h1>User List</h1>
    <div>
        <label for="filter_country">Filter by Country:</label>
        <select name="filter_country" id="filter_country">
            <option value="">All</option>
            @foreach ($countries as $country)
                <option value="{{ $country->name }}">{{ $country->name }}</option>
            @endforeach
        </select>
        <br>
        <label for="filter_date">Filter by Date:</label>
        <input type="date" name="filter_date" id="filter_date">
    </div>
    <br>
    <table id="users-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Country</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('users.index') !!}',
                    data: function (d) {
                        d.filter_country = $('#filter_country').val();
                        d.filter_date = $('#filter_date').val();
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'country_name', name: 'country_name'},
                    {data: 'userDetail_gender', name: 'userDetail_gender'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });

            // Handle filter changes and reload the table
            $('#filter_country, #filter_date').change(function () {
                $('#users-table').DataTable().ajax.reload();
            });
        });
    </script>
@endsection
The index.blade.php view now includes a data table powered by DataTables library. The table has filter options for country and date. When you select a country or date, the table will be updated using AJAX without requiring a page reload.

Make sure to include the required assets and JavaScript libraries (jQuery, DataTables) in your layout file (layouts/app.blade.php) for this to work.

Please ensure you have the necessary JavaScript and CSS assets properly loaded in your layout file or add them as needed. Also, adjust the Blade templates and JavaScript code according to your application's design and requirements.






Regenerate
Send a message

Free Research Preview. ChatGPT may produce inaccurate information about people, places, or facts. ChatGPT July 20 Version


       /**
     * Store a new user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Create a new User instance and fill it with the input data
        if($request->gender){
            $userdetail = UserDetail::create([
                'gender' => $request->gender,
            ]);

        }
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->country_id = $request->input('country_id');
        $user->gender = $userdetail->id;
        $user->password = Hash::make($request->input('password'));
        
        $user->save();
       
        
        // dd($user);
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
        if($request->gender){
            
            $userdetail = UserDetail::create([
                'gender' => $request->gender,
            ]);

        }
        
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        // Update the user's information with the input data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->user_detail_id = $userdetail->id;
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
