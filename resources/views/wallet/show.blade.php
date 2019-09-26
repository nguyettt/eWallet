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
                window.location.href = "wallet/{{ $id }}?time="+time+"#"+date;
            });
        })  
    </script>
@endpush

@section('title')
    @if (isset($wallet)) {{ $wallet['name'] }} @else Undefined @endif
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        @if (isset($wallet))
        <div class="card-body">
            <h3>Balance: {{ number_format($wallet->balance, 2) }} đ</h3>
        </div>
        @endif

        <div class="card-header row justify-content-center">
            <div class="row col-lg-12 justify-content-between">
                <a href="wallet/{{ $id }}?time={{ $prev }}">
                    <i class="fas fa-arrow-left fa-fw text-dark"></i>
                </a>
                <h4 class="font-weight-bold">{{ $time }}</h4>
                <a href="wallet/{{ $id }}?time={{ $next }}">
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
                        <td><h4 class="float-right text-success">{{ number_format($flow['in'], 2) }} đ</p></h4>
                    </tr>
                    <tr>
                        <th>Outflow</th>
                        <td><h4 class="float-right text-danger">{{ number_format($flow['out'], 2) }} đ</p></h4>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        
        @foreach ($records as $date => $item)
        <div class="row">
            <a href="wallet/{{ $id }}?time={{ date('d-m-Y', strtotime($date)) }}" class="row col-lg-12 pr-0 text-dark">
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
                <div class="col-lg-4 p-0 d-flex">                    
                    <div class="col-lg-12 p-0 mt-auto mb-auto">
                        <h5 class="@if ($item['sum'] < 0) text-danger @else text-success @endif d-flex justify-content-end">{{ number_format(abs($item['sum']), 2) }} đ</h5>
                    </div>
                </div>
            </div>
            </a>
            <hr style="width: 100%;">
        </div>
        
        @if (end($records) != $item)
        <div class="row p-3"></div>
        @endif

        @endforeach
    </div>   
</div>
@endsection
