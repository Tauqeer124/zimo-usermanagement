<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(){
        $country = Country::get();
        return view('country.index');
    }
    public function store(Request $request){
        $contry
    }
}
