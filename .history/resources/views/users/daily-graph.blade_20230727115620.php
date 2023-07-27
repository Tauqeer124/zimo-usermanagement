<!-- resources/views/users/daily-graph.blade.php -->

@extends('layouts.app')

@section('content')
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <h1>Daily User Registration Graph</h1>
    <div id="chartContainer" style="height: 400px; width: 100%;"></div>

    <script src="{{ asset('js/canvasjs.min.js') }}"></script>
    <script>
        window.onload = function() {
        var dates = @json($dates);
        var userCounts = @json($userCounts);

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Daily User Registration"
            },
            axisX: {
                title: "Date",
                valueFormatString: "YYYY-MM-DD",
            },
            axisY: {
                title: "Number of Registrations",
            },
            data: [{
                type: "line",
                dataPoints: [
                    @for ($i = 0; $i < count($dates); $i++)
                        { x: new Date("{{ $dates[$i] }}"), y: {{ $userCounts[$i] }} },
                    @endfor
                ]
            }]
        });
        chart.render();
    }
    </script>
    
@endsection
