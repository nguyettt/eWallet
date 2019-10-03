@extends('layouts.app')

@push('script')
    <script src="js/search.js"></script>
@endpush

@push('style')
    <link rel="stylesheet" href="css/transaction.css"></link>
@endpush

@section('title')
    Transactions
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row">
            <div class="col-lg-2 p-0 d-flex justify-content-start">
                <a href="/dashboard" class="btn btn-success mb-auto mt-auto">Back</a>
            </div>
            <div class="col-lg-8 d-flex justify-content-center">
                <h4 class="mb-auto mt-auto">Filter</h4>
            </div>
            
            <hr style="width: 80%">

            <div class="row col-lg-12 justify-content-center">
                <form method="POST" action="javascript:void(0)" class="col-lg-12 justify-content-center" >
                    <div class="form-group row justify-content-center">
                        <label for="wallet" class="col-lg-2 col-form-label text-lg-right px-0">Wallet:</label>
                        <div class="col-lg-10">
                            <select name="wallet" id="wallet_id" class="form-control">
                                <option value="" disabled hidden selected>Select wallet</option>
                                <option value="all">All</option>
                                @foreach ($wallet as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <label for="cat" class="col-lg-2 col-form-label text-lg-right px-0">Category:</label>
                        <div class="col-lg-10">
                            <select name="cat" id="cat_id" class="form-control">
                                <option value="" disabled hidden selected>Select category</option>
                                <option value="all">All</option>
                                @foreach ($cat as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-lg-2 col-form-label text-lg-right px-0">
                            <input type="checkbox" id="include" name="ckInclude" value="yes" checked>
                        </div>
                        <div class="col-lg-10 d-flex">
                            <span class="mt-auto mb-auto">Include all sub-categories</span>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <label for="start" class="col-lg-2 col-form-label text-lg-right px-0">From:</label>
                        <div class="col-lg-10">
                            <input type="date" id="start" name="start" value="" class="form-control"></input>
                        </div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <label for="end" class="col-lg-2 col-form-label text-lg-right px-0">To:</label>
                        <div class="col-lg-10">
                            <input type="date" id="end" name="end" value="" class="form-control"></input>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="row col-lg-12 justify-content-end pr-0">
                            <button class="btn btn-success" id="search">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div id="result" class="col-lg-12 p-0">
                
            </div>
        </div>
    </div>
</div>
@endsection
