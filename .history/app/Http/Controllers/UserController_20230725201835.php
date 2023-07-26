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
        if ($request->ajax()) {
            $data = User::select('*');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $showRoute = url('users', $row->id);
                        $editRoute = route('users.edit', $row->id);
                        $deleteRoute = route('user.delete', $row->id);
                        $btn = '<a href="'.$showRoute.'" class="btn btn-primary btn-sm">Show</a> <a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>  <a href="'.$editRoute.'" class="btn btn-danger btn-sm">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('users.index');
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
        Session::flash('success', 'User created successfully.');
        return response()->json(['success' => true]);
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
    public function showuserdata($country){
        
        // $usersdata = User::select(DB::raw('country, count(*) as count'))
        // ->groupBy('country')
        // ->get();
        dd("usersdata");
        return view('users.data', compact('usersdata'));
    }
}
