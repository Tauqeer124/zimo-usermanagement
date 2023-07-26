@extends('layouts.app')

@section('content')
<head>
    <title>User Graph by kkCountry</title>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>
<body>
    
    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
    <script>
        window.onload = function () {
            const data = @json($usersByCountry );

            const dataPoints = data.map(item => ({
                label: item.country,
                y: parseInt(item.count)
            }));

            const chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "User Graph by Country"
                },
                data: [{
                    type: "column",
                    click: function (e) {
                        var country = e.dataPoint.label;
                        window.location.href = "" + '/' + country;
                    },

                    dataPoints: Object.entries(data).map(([country, count]) => ({ label: country, y: count }))
                }]
                }]
            });

            chart.render();
        }
    </script>
    <script type="text/javascript">
        window.onload = function () {
            var data = @json($data);

            var chart = new CanvasJS.Chart("userGraph", {
                title: {
                    text: "User Graph by Country"
                },
                data: [{
                    type: "column",
                    click: function (e) {
                        var country = e.dataPoint.label;
                        window.location.href = "{{ route('user.data', '') }}" + '/' + country;
                    },
                    dataPoints: Object.entries(data).map(([country, count]) => ({ label: country, y: count }))
                }]
            });

            chart.render();
        }
    </script>
</body>
@endsection
