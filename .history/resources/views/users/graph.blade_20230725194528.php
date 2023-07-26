@extends('layouts.app')

@section('content')
<head>
    <title>User Graph by kkCountry</title>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body>
    
    <body>
        <div id="userGraph" style="height: 300px; width: 100%;"></div>
    
        <script type="text/javascript">
            window.onload = function () {
                var data = @json($usersByCountry);
    
                var chart = new CanvasJS.Chart("userGraph", {
                    title: {
                        text: "User Graph by Country"
                    },
                    data: [{
                        type: "column",
                        click: function (e) {
                            var country = e.dataPoint.label;
                            window.location.href = "{{ route(" + '/' + country;
                        },
                        dataPoints: Object.entries(data).map(([country, count]) => ({ label: country, y: count }))
                    }]
                });
    
                chart.render();
            }
        </script>
    </body>
    
@endsection
