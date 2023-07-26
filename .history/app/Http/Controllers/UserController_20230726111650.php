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
    public function index(Request $request)
    {
        // $users = User::all();
        // return view('users.index', compact('users'));
        $countries = User::pluck('country')->unique();
        if ($request->ajax()) {
            $data = User::select('*');
             // Apply date filter if provided
        if ($request->has('filter_date')) {
            $filterDate = $request->input('filter_date');
            $filterDateFormatted = date('Y-m-d', strtotime($filterDate));
            $data->whereDate('created_at', '=', $);
        }

        // Apply country filter if provided
        if ($request->has('filter_country')) {
            $filterCountry = $request->input('filter_country');
            $data->where('country', 'LIKE', '%' . $filterCountry . '%');
        }
        
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('created_at', function ($row) {
                        return $row->created_at->format('Y-m-d H:i:s ');
                    })
                    ->addColumn('action', function ($row) {
                        $showRoute = url('users', $row->id);
                        $editRoute = route('users.edit', $row->id);
                        $deleteRoute = route('user.delete', $row->id);
                        $btn = '<a href="'.$showRoute.'" class="btn btn-primary btn-sm">Show</a> <a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>  <a href="'.$editRoute.'" class="btn btn-danger btn-sm">Delete</a>';
                        return $btn;
                        
                    })
                    ->rawColumns(['created_at', 'action'])
                    
                    ->make(true);
        }
        return view('users.index', compact('countries'));
    }
    public function index3(Request $request){
    $users = User::select(['id', 'name', 'email', 'phone', 'country', 'status', 'created_at']);

    // Apply date filter if provided
    if ($request->has('start_date') && $request->has('end_date')) {
        $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
        $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
        $users->whereBetween('created_at', [$start_date, $end_date]);
    }

    // Apply country filter if provided
    if ($request->has('country')) {
        $country = $request->input('country');
        $users->where('country', $country);
    }

    return DataTables::of($users)
        ->addColumn('action', function ($user) {
            $status = $user->status == 'active' ? 'block' : 'active';
            return '<button class="btn-toggle-status" data-user-id="' . $user->id . '" data-status="' . $status . '">' . ucfirst($status) . '</button>';
        })
        ->addColumn('created_at_formatted', function ($user) {
            return $user->created_at->format('Y-m-d H:i:s');
        })
        ->toJson();
    }
    public function create(){
        return view();
    }
    public function store(Request $request){
        
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->country = $request->input('country');
        $user->password = Hash::make($request->input('password'));
        
        $user->save();
        Session::flash('message', 'User created successfully.');
        // return response()->json(['success' => true]);
        return redirect()->route('user.index')->with('success','User created successfully.');
    }

    public function show($id)
    {
        // dd("kdkd");
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function destroy($id)
    {
        User::find($id)->delete();
     
        return back();
    }
    public function edit($id){
        $user= User::find($id);
        return response()->json($user);
    }
    public function update(Request $request , $id){
        // dd("helo");
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->country = $request->input('country');
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        
        $user->save();
        return response()->json(['success' => true]);
    }
    public function dashboard(){
        return view('dashboard');
    }
    //show graph
    public function showgraph()
    {
        // $users = User::all();
        // $totaluser = $users->groupBy('country')->map->count();
// dd($data);
$usersByCountry = User::select(DB::raw('country, count(*) as count'))
        ->groupBy('country')
        ->get();
// dd($usersByCountry);
        return view('users.graph', compact('usersByCountry'));
    }
    public function showuserdata(Request $request, $country){
        // if ($request->ajax()) {
        $users = User::where('country', $country)->get();
        
        // return DataTables::of($users)
        // ->addIndexColumn()
        // ->rawColumns(['action'])
        // ->make(true);
        // return view('users.data');
        // }
        return view('users.data', compact('users','country'));
    }
}
