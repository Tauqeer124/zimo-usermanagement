<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDetailController extends Controller
{
    public function index()
    {
        $userDetails = UserDetail::all();
        return view('user_details.index', compact('userDetails'));
    }
}
