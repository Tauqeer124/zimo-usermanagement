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
        // $usersByCountry = User::with('country')
        // ->select(DB::raw('country_id as country, count(*) as count'))
        // ->groupBy('country_id')
        // ->get();
        $data = User::select('users.*', 'countries.name as country')
        ->leftJoin('countries', 'users.country_id', '=', 'countries.id');
        
        $usersByCountry = User::with('country')
    ->select(DB::raw('country_id, count(*) as count'))
    ->groupBy('country_id')
    ->get(['country_id', 'count', 'country.name']);
      dd($usersByCountry);

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
