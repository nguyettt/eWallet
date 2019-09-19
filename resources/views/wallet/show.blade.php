@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/wallet.css">
@endpush

@push('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {

            $("#calendar").click(function (e) {
                $("#datepicker").datepicker({
                    dateFormat: "dd-mm-yy"
                });
                e.preventDefault();
                $("#datepicker").datepicker("show");
            })

            $("#datepicker").change(function () {
                var date = $("#datepicker").val();
                var time = date.substring(3, 10);
                window.location.href = "wallet/{{ $wallet->id }}?time="+time+"#"+date;
            });
        })  
    </script>
@endpush

@section('title')
    {{ $wallet['name'] }}
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row justify-content-center">
            <div class="row col-lg-12 justify-content-between">
                <a href="wallet/{{ $wallet->id }}?time={{ $prev }}">
                    <i class="fas fa-arrow-left fa-fw text-dark"></i>
                </a>
                <h4 class="font-weight-bold">{{ $time }}</h4>
                <a href="wallet/{{ $wallet->id }}?time={{ $next }}">
                    <i class="fas fa-arrow-right fa-fw text-dark"></i>
                </a>
            </div>
            <div class="col-lg-12 row justify-content-end">
                <input type="hidden" id="datepicker" value="">
                <a href="#" id="calendar" class="btn btn-success">Jump to date</a>
            </div>
            <div class="row col-lg-12 p-0">
                <table class="table table-borderless">
                    <tr>
                        <th>Inflow</th>
                        <td><h4 class="float-right text-success">{{ number_format($inflow, 2) }} đ</p></h4>
                    </tr>
                    <tr>
                        <th>Outflow</th>
                        <td><h4 class="float-right text-danger">{{ number_format($outflow, 2) }} đ</p></h4>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        @foreach ($records as $date => $item)
        <div class="row">
            <div class="row col-lg-12" id="{{ date('d-m-Y', strtotime($date)) }}">
                <div class="col-lg-2 p-0 d-flex justify-content-center">
                    <h1 class="mt-auto mb-auto">{{ date('d', strtotime($date)) }}</h1>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="col-lg-12 p-0">
                        <h6 class="mt-auto mb-auto">{{ date('l', strtotime($date)) }}</h6>
                    </div>
                    <div class="col-lg-12 p-0">
                        <span class="mt-auto mb-auto">{{ date('F', strtotime($date)) }}</span>
                    </div>
                </div>
                <div class="col-lg-4 pr-0">
                    <h5 class="float-right @if ($item['sum'] < 0) text-danger @else text-success @endif">{{ number_format(abs($item['sum']), 2) }} đ</h5>
                </div>
            </div>
            <hr style="width: 100%;">
            @foreach ($item as $key => $record)
                @if (is_array($record))
                    <div class="col-lg-12 row pr-0">
                        <a href="transaction/{{ $record['id'] }}" class="row col-lg-12 pr-0 text-dark">
                            <div class="col-lg-8 mb-3">
                                <div class="col-lg-12">
                                    <h6 class="mt-auto mb-auto">{{ date('H:i:s', strtotime($record['created_at'])) }}</h6>
                                </div>
                                <div class="col-lg-12">
                                    <h6 class="mt-auto mb-auto font-weight-bold">{{ $record['cat_name'] }}</h6>
                                </div>
                                <div class="col-lg-12">
                                    <span class="mt-auto mb-auto">{{ $record['details'] }}</span>
                                </div>
                            </div>
                            <div class="col-lg-4 pr-0">
                                <span class="mt-auto mb-auto float-right @if ($record['type'] == 1) text-success @else text-danger @endif">{{ number_format($record['amount'], 2) }} đ</span>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
        
        <div class="row p-3"></div>
        @endforeach
    </div>   
</div>
@endsection
