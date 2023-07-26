<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        return view('users.add');
    }
    public function store(Request $request){
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
        return redirect()->route('user.index');
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
     
        return response()->json(['success'=>'Post deleted successfully.']);
    }
    public function edit($id){
        $user= User::find($id);
        return view('users.edit' );
    }
    public function dashboard(){
        return view('dashboard');
    }
}
