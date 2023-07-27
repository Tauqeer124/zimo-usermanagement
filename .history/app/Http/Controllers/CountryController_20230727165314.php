<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(){
        $country = Country::get();
        return view('country.index' ,compact('country'));
    }
    public function store(Request $request){
        $country = new Country();
        $country->name = $request->input('name');
        $country->save();
        return redirect()->route('country.index')->with('success', 'country created successfully.');
    }
}
