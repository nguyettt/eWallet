@extends('layouts.app')

@push('script')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@if ($inflow > 0 || $outflow > 0)
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
            height: 500,
            legend: {
                alignment: 'center',
                position: 'bottom'
            },
            titlePosition: 'none',
            chartArea: {
                top: 10,
            }
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
            if ($("#content").hasClass("active")) {
                var _width = ($(window).width() - (48*2) - (48*2) - (20*2)) / 2;
            } else {
                var _width = ($(window).width() - (48*2) - (48*2) - (20*2) - 320) / 2;
            }
            drawChart(_width);
        })
    })
</script>
@endif

@endpush

@push('style')
<link rel="stylesheet" href="css/dashboard.css"></link>
@endpush

@section('title')
    Dashboard
@endsection

@section('content')
<div class="container-fluid">
    <div class="card bg-light p-5 col-lg-12">
        <div class="card-header row justify-content-center">
            <h1 class="font-weight-bold">Overview</h1>
        </div>
        <div class="row col-lg-12">
            @if ($inflow > 0 || $outflow > 0)
            <div class="col p-0">
                <div class="col-lg-12 d-flex justify-content-center pt-3"><h4>{{ date('m - Y') }}</h4></div>
                <div id="pie_chart" class="col-lg-12"></div>
            </div>

            <div class="col p-0 d-flex align-items-center">
                <div class="col-lg-12">
                    <div class="row col-lg-12 justify-content-end p-3">
                        <h4 class="text-dark">Starting balance: {{ number_format($startingBalance, 2) }} đ</h4>
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
            @else
            <div class="col-lg-12 d-flex justify-content-center p-5">
                <h4>There are no transaction yet</h4>
            </div>
            @endif
        </div>

        <hr style="width: 100%">

        <div class="col-lg-12">
            <div class="row col-lg-12">
                <div class="col-lg-6">
                    <div class="col-lg-12">
                        <h4>Top Income</h4>
                    </div>
                    @foreach ($top_income as $item)
                    <div class="col-lg-12 row p-3">
                        <a href="transaction/{{ $item->id }}" class="row col-lg-12 pr-0 text-dark">
                            <div class="col">
                                <div class="col-lg-12">
                                    <h6 class="mt-auto mb-auto">{{ date('H:i:s', strtotime($item->created_at)) }}</h6>
                                </div>
                                <div class="col-lg-12">
                                    <span class="mt-auto mb-auto h6">{{ $item->details }}</span>
                                </div>
                            </div>
                            <div class="col pr-0 d-flex justify-content-end">
                                <h5 class="mt-auto mb-auto text-success">{{ number_format($item->amount, 2) }} đ</h5>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

                <div class="col-lg-6">
                    <div class="col-lg-12">
                        <h4>Top Outcome</h4>
                    </div>
                    @foreach ($top_outcome as $item)
                    <div class="col-lg-12 row p-3">
                        <a href="transaction/{{ $item->id }}" class="row col-lg-12 pr-0 text-dark">
                            <div class="col">
                                <div class="col-lg-12">
                                    <h6 class="mt-auto mb-auto">{{ date('H:i:s', strtotime($item->created_at)) }}</h6>
                                </div>
                                <div class="col-lg-12">
                                    <span class="mt-auto mb-auto h6">{{ $item->details }}</span>
                                </div>
                            </div>
                            <div class="col pr-0 d-flex justify-content-end">
                                <h5 class="mt-auto mb-auto text-danger">{{ number_format($item->amount, 2) }} đ</h5>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <hr style="width: 100%">
    </div>
</div>
@endsection
