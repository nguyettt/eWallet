@extends('layouts.app')

@push('style')
    <link rel="stylesheet" href="css/transaction.css"></link>
@endpush

@section('title')
    Transaction {{ $trans->id }}
@endsection

@push('script')
    <script>
    $(function () {
        $("#delete").click(function (e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this record?")) {
                $("#delTransaction").submit();
            }
        })
    })
    </script>
@endpush

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-body row">
            <div class="row col-lg-12 justify-content-end p-0">
                <a href="transaction/{{ $trans->id }}/edit">
                    <i class="fas fa-edit fa-fw text-primary mr-3"></i>
                </a>
                <a href="#" id="delete">
                    <i class="fas fa-trash-alt fa-fw text-danger"></i>
                </a>
                <form id="delTransaction" method="POST" action="/transaction/{{ $trans->id }}">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="row col-lg-12 p-3"></div>
            <table class="table table-borderless">
                <tr>
                    <td>
                        <h4>
                            <i class="fas fa-bookmark fa-fw text-dark"></i>
                        </h4>
                    </td>
                    <td>
                        <h4>{{ $trans->category->name }}</h4>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <h3 class="@if ($trans->category->type == 1) text-primary @else text-danger @endif">{{ number_format($trans->amount) }} đ</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <i class="fas fa-align-left fa-fw text-dark"></i>
                        </span>
                    </td>
                    <td>
                        <span>{{ $trans->details }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <i class="fas fa-calendar-alt fa-fw text-dark"></i>
                        </span>
                    </td>
                    <td>
                        <span>{{ date('d-m-Y H:i:s', strtotime($trans->created_at)) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>
                            <i class="fas fa-wallet fa-fw text-dark"></i>
                        </span>
                    </td>
                    <td>
                        <span>{{ $trans->wallet->name }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>   
</div>
@endsection
