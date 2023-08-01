<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    /**
     * Generate and display a graph of user data by country.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showgraph()
    {
        // Fetch user data grouped by country
        // $usersByCountry = User::select(DB::raw('country_id as country, count(*) as count'))
        //     ->groupBy('country_id')
        //     ->get();
        $usersByCountry11 = User::with('country')
        ->select(DB::raw('country_id as country, count(*) as count'))
        ->groupBy('country_id')
        ->get();
        foreach ($usersByCountry11 as $userData) {
            // Access the country name from the relationship
            $countryName = $userData->country->name;
            $ = $userData->count;
        
            // Now you can use $countryName and $userCount as needed.
        }
            // dd($usersByCountry);

        return view('users.graph', compact('usersByCountry'));
    }
    public function showgraph2(){
        $usersByCountry = User::withCount('country')->get();
        // dd($usersByCountry);
        return view('users.graph', compact('usersByCountry'));
    }
    public function dailyUserRegistrationGraph()
    {
        $today = Carbon::today();
        $dates = [];
        $userCounts = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $today->subDays($i);
            $dates[] = $date->format('Y-m-d');

            $count = User::whereDate('created_at', $date)->count();
            $userCounts[] = $count;
        }

        $dates = array_reverse($dates);
        $userCounts = array_reverse($userCounts);

        return view('users.daily-graph', compact('dates', 'userCounts'));
    }
}
