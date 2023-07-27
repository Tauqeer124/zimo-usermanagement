<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
        $usersByCountry = User::select(DB::raw('country, count(*) as count'))
            ->groupBy('country')
            ->get();

        return view('users.graph', compact('usersByCountry'));
    }
    use Illuminate\Support\Facades\Log; // Add this at the top of your controller

public function dailyUserRegistrationGraph()
{
    try {
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
    } catch (\Exception $e) {
        Log::error('Error in dailyUserRegistrationGraph(): ' . $e->getMessage());
        // If an error occurs, you may want to display a friendly error page or message.
        // For simplicity, we'll just redirect to the home page.
        return redirect()->route('home')->with('error', 'An error occurred while generating the graph.');
    }
}

}
