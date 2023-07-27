<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        // Get unique countries to use as filters
        $countries = User::pluck('country')->unique();

        if ($request->ajax()) {
            $data = User::select('*');

            // Apply date filter if provided
            // if ($request->has('filter_date')) {
            //     $filterDate = $request->input('filter_date');
            //     $data->whereDate('created_at', '=', $filterDate);
            // }
            // Apply date filter if provided
if ($request->has('filter_date')) {
    $filterDate = $request->input('filter_date');
    $formattedDate = date('Y-m-d H:i:s', strtotime($filterDate));
    $data->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') = '$formattedDate'");
}


            // Apply country filter if provided
            if ($request->has('filter_country')) {
                $filterCountry = $request->input('filter_country');
                $data->where('country', 'LIKE', '%' . $filterCountry . '%');
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $showRoute = url('users', $row->id);
                    $editRoute = route('users.edit', $row->id);
                    $deleteRoute = route('user.delete', $row->id);
                    $btn = '<a href="'.$showRoute.'" class="btn btn-primary btn-sm">Show</a> <a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>  <a href="'.$editRoute.'" class="btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })
                ->addC
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
        $user->country = $request->input('country');
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
        $user->country = $request->input('country');
        
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
     * Generate and display a graph of user data by country.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showgraph()
    {
        // Fetch user data grouped by country
        $usersByCountry = User::select(DB::raw('country, count(*) as count'))
            ->groupBy('country')
            ->get();

        return view('users.graph', compact('usersByCountry'));
    }

    /**
     * Display user data for a specific country.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $country
     * @return \Illuminate\Contracts\View\View
     */
    public function showuserdata(Request $request, $country)
    {
        // Fetch users from the specific country
        $users = User::where('country', $country)->get();
        return view('users.data', compact('users', 'country'));
    }

    /**
     * Change the status of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        // Find the user with the given ID and update the status
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => 'Status changed successfully.']);
    }
}
