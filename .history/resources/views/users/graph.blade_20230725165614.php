<!-- resources/views/user/graph.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>User Graph by Country</title>
    <script src="{{ asset('js/canvasjs.min.js') }}"></script>
</head>
<body>
    <div id="userGraph" style="height: 300px; width: 100%;"></div>

    <script type="text/javascript">
        window.onload = function () {
            var data = @json($data);

            var chart = new CanvasJS.Chart("userGraph", {
                title: {
                    text: "User Graph by Country"
                },
                data: [{
                    type: "column",
                    dataPoints: Object.entries(data).map(([country, count]) => ({ label: country, y: count }))
                }]
            });

            chart.render();
        }
    </script>
</body>
</html>
