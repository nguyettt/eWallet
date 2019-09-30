@extends('layouts.app')

@section('title')
    @if (isset($wallet)) {{ $wallet->name }} @else Undefined @endif : {{ $date }}
@endsection

@push('style')    
    <link rel="stylesheet" href="css/wallet.css">
@endpush

@push('script')
    <script>
    $(function () {
        var date = "{{ $date }}";
        var time = date.split(" - ");
        $("#backLink").attr("href", "wallet/{{ $id }}?time=" + time[1] + "-" + time[2]);
    })
    </script>
@endpush

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row justify-content-center">
            <div class="col-lg-12 row">
                <div class="col-lg-2 p-0 d-flex justify-content-start">
                    <a id="backLink" href="#" class="btn btn-success">Back</a>
                </div>
                <div class="col-lg-8 d-flex justify-content-center">
                    <h4 class="font-weight-bold">{{ $date }}</h4>
                </div>
            </div>
            <div class="col-lg-12">
                <table class="table table-borderless">
                    <tr>
                        <th>Inflow</th>
                        <td><h4 class="float-right text-success">{{ number_format($flow['in']) }} đ</p></h4>
                    </tr>
                    <tr>
                        <th>Outflow</th>
                        <td><h4 class="float-right text-danger">{{ number_format($flow['out']) }} đ</p></h4>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-body row">
        @foreach ($transaction as $key => $record)
                <div class="col-lg-12 row pr-0">
                    <a href="transaction/{{ $record['id'] }}" class="row col-lg-12 pr-0 text-dark">
                        <div class="col-lg-8">
                            <div class="col-lg-12">
                                <h6 class="mt-auto mb-auto">{{ date('H:i:s', strtotime($record['created_at'])) }}</h6>
                            </div>
                            <div class="col-lg-12">
                                <span class="mt-auto mb-auto h6">{{ $record['details'] }}</span>
                            </div>
                        </div>
                        <div class="col-lg-4 pr-0 d-flex justify-content-end">
                            @if (isset($wallet))
                                <h5 class="mt-auto mb-auto @if ($record['type'] == 1 || $record['benefit_wallet'] == $wallet->id) text-success @else text-danger @endif">{{ number_format($record['amount']) }} đ</h5>
                            @else
                            <h5 class="mt-auto mb-auto @if ($record['type'] == 1) text-success @else text-danger @endif">{{ number_format($record['amount']) }} đ</h5>
                            @endif
                        </div>
                    </a>
                </div>
                <hr style="width:100%">
        @endforeach
        </div>
    </div>
</div>
@endsection
