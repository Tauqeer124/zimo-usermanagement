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
                ->addColumn('action', function ($row) {
                    $showRoute = route('user.show', $row->id);
                    // $editRoute = route('user.edit11', $row->id);
                    $deletetRoute = route('user.delete', $row->id);
                    $btn = '<a href="'.$showRoute.'" class="btn btn-primary btn-sm">Show</a> <a href="'.$editRoute.'" class="btn btn-info btn-sm">Edit</a> <a href="'.$deleteRoute.'" class="btn btn-info btn-sm">Edit</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index');
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
