@extends('layouts.app')

@push('script')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);    
    var width = $("#pie_chart").width();
    function drawChart(width) {
        var data = google.visualization.arrayToDataTable([
            ['Type', 'Amount'],
            ['Inflow', {{ $inflow }}],
            ['Outflow', {{ $outflow }}]
        ]);

        // Optional; add a title and set the width and height of the chart
        var options = {
            title:'Money flow',
            backgroundColor:'#f8f9fa',
            colors: ['#28a745', '#dc3545'],
            width: width,
            height: 400,
            legend: {
                alignment: 'center',
                position: 'bottom'
            },
            titlePosition: 'none',
        };

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
        chart.draw(data, options);
    }

    $(window).resize(function () {        
        var _width = $("#pie_chart").width();
        drawChart(_width);
    })

    $(function () {
        $("#sidebarCollapse").click(function () {
            // drawChart();
            // console.log('ye');
            if ($("#content").hasClass("active")) {
                var _width = ($(window).width() - (48*2) - (48*2) - (20*2)) / 2;
            } else {
                var _width = ($(window).width() - (48*2) - (48*2) - (20*2) - 320) / 2;
            }
            drawChart(_width);
        })
    })
</script>
@endpush

@section('title')
    Dashboard
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-light p-5 col-lg-12">
        <div class="card-header row justify-content-center">
            <h1 class="font-weight-bold">{{ date('m - Y') }}</h1>
        </div>
        <div class="row col-lg-12">
            <div class="col-lg-6 p-0">
                <div id="pie_chart" class="col-lg-12"></div>
            </div>
            <div class="col-lg-6 p-0 d-flex align-items-center">
                <div class="col-lg-12">
                    <div class="row col-lg-12 justify-content-end p-3">
                        <h4 class="text-dark">Startingbalance: {{ number_format($startingBalance, 2) }} đ</h4>
                    </div>
                    <div class="row col-lg-12 justify-content-end p-3">
                        <h4 class="text-dark">Ending balance: {{ number_format($balance, 2) }} đ</h4>
                    </div>
                    <div class="row col-lg-12 justify-content-end p-3">
                        <h4 class="text-success">Inflow: {{ number_format($inflow, 2) }} đ</h4>
                    </div>
                    <div class="row col-lg-12 justify-content-end p-3">
                        <h4 class="text-danger">Outflow: {{ number_format($outflow, 2) }} đ</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-lg-12">

        </div>
    </div>
</div>
@endsection
