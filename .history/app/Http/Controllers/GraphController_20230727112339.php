<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
