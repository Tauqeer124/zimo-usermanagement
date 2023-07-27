<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $usersByCountry = User::select(DB::raw('country, count(*) as count'))
            ->groupBy('country')
            ->get();

        return view('users.graph', compact('usersByCountry'));
    }
    // ... Controller Code ...
    public function 

$today = Carbon::today();
$dates = [];
$userCounts = [];

for ($i = 0; $i < 7; $i++) {
    $date = $today->subDays($i);
    $dates[] = $date->format('Y-m-d');

    $count = User::whereDate('created_at', $date)->count();
    $userCounts[] = $count;
}

// ... Rest of the Controller Code ...

// Fill in missing dates with zero user counts
$completeDates = [];
$completeUserCounts = [];

$endDate = Carbon::parse(end($dates));

while ($endDate >= $today) {
    $formattedDate = $today->format('Y-m-d');
    $index = array_search($formattedDate, $dates);

    if ($index !== false) {
        $completeDates[] = $formattedDate;
        $completeUserCounts[] = $userCounts[$index];
    } else {
        $completeDates[] = $formattedDate;
        $completeUserCounts[] = 0;
    }

    $today->addDay();
}

$dates = array_reverse($completeDates);
$userCounts = array_reverse($completeUserCounts);

return view('users.daily-graph', compact('dates', 'userCounts'));

}
