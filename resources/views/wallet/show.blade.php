@extends('layouts.app')

@section('title')
    {{ $wallet['name'] }}
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row justify-content-center">
            <h4 class="font-weight-bold">{{ __('Overview') }}</h4>
            <hr>
            <table class="table table-borderless">
                <tr>
                    <td>Inflow</td>
                    <td><p class="float-right text-success">{{ number_format($inflow, 2) }} đ</p></td>
                </tr>
                <tr>
                    <td>Outflow</td>
                    <td><p class="float-right text-danger">{{ number_format($outflow, 2) }} đ</p></td>
                </tr>
            </table>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-2 p-0 d-flex justify-content-center">
                <h4 class="mt-auto mb-auto">09</h4>
            </div>
            <div class="col-lg-6 p-0">
                <div class="col-lg-12 p-0">
                    <h6 class="mt-auto mb-auto">Monday</h6>
                </div>
                <div class="col-lg-12 p-0">
                    <span class="mt-auto mb-auto">September</span>
                </div>
            </div>
            <div class="col-lg-4">
                <span class="float-right">800000</span>
            </div>
        </div>
        <hr>
        <div class="row">
            Name
        </div>
    </div>
</div>
@endsection
