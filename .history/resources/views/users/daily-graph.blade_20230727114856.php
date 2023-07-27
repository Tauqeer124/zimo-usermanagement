<!-- resources/views/users/daily-graph.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Daily User Registration and User Graph by Country</h1>
    <div id="chartContainer" style="height: 400px; width: 100%;"></div>

    <script src="{{ asset('js/canvasjs.min.js') }}"></script>
    <script>
        var dates = @json($dates);
        var userCounts = @json($userCounts);
        var usersByCountry = @json($usersByCountry);

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Daily User Registration and User Graph by Country"
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
                name: "Daily User Registration",
                showInLegend: true,
                dataPoints: [
                    @for ($i = 0; $i < count($dates); $i++)
                        { x: new Date("{{ $dates[$i] }}"), y: {{ $userCounts[$i] }} },
                    @endfor
                ]
            }, {
                type: "column",
                name: "User Graph by Country",
                showInLegend: true,
                dataPoints: usersByCountry.map(item => ({
                    label: item.country,
                    y: parseInt(item.count),
                    url: "/user/data/" + item.country
                })),
                click: function(event) {
                    if (event.dataPoint && event.dataPoint.url) {
                        window.location = event.dataPoint.url;
                    }
                }
            }]
        });
        chart.render();
    </script>
@endsection
