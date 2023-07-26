@extends('layouts.app')

@section('content')
<head>
    <title>User Graph by kkCountry</title>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<!-- Existing content... -->

<body>
    <div id="chartContainer" style="height: 300px; width: 100%;"></div>

    <script>
        window.onload = function() {
            const data = @json($usersByCountry);

            const dataPoints = data.map(item => ({
                label: item.country,
                y: parseInt(item.count),
                url: "/user/" + item.country
            }));

            const chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "User Graph by Country"
                },
                data: [{
                    type: "column",
                    dataPoints: dataPoints,
                    click: function(event) {
                        if (event.dataPoint && event.dataPoint.url) {
                            window.location = event.dataPoint.url;
                        }
                    }
                }]
            });

            chart.render();
        }
    </script>
</body>

@endsection
