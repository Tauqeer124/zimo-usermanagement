<?php

namespace App\Http\Controllers;

use App\Models\User;
use DataTables;
use Illuminate\Http\Request;

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
     
                           $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>  <a href="javascript:void(0)" class="edit btn btn-primary btn-sm"> edit</a>  <a href="javascript:void(0)" class="edit btn btn-danger  btn-sm">delete</a>';
                            
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
