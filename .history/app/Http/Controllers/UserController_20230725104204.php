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
                    ->addColumn('action', function($row){
                        $showRoute = route('user.show', $row->id);
                           $btn = '<a href=".." class="edit btn btn-primary btn-sm">View</a>  <a href="javascript:void(0)" class="edit btn btn-primary btn-sm"> edit</a>  <a href="javascript:void(0)" class="edit btn btn-danger  btn-sm">delete</a>';
                            
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
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function dashboard(){
        return view('dashboard');
    }
}
