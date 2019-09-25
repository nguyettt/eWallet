@extends('layouts.app')

@push('script')
<script src="js/transaction.js"></script>
@endpush

@section('title')
    Add new transaction
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row justify-content-center">
            <h5 class="font-weight-bold">{{ __('New Transaction') }}</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="transaction">
                @csrf
                <div class="form-group row">
                    <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>

                    <div class="col-md-6">
                        <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror"
                            name="amount" value="{{ old('amount') }}" required autocomplete="amount" autofocus>

                        @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="details" class="col-md-4 col-form-label text-md-right">{{ __('Detail') }}</label>

                    <div class="col-md-6">
                        <input id="details" type="text" class="form-control @error('details') is-invalid @enderror"
                            name="details" value="{{ old('details') }}" autocomplete="details" autofocus>

                        @error('details')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                    <div class="col-md-6">
                        <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="" disabled hidden selected>Select</option>
                            <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Income</option>
                            <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Expense</option>
                            <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Transfer to another wallet</option>
                        </select>

                        @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row cat">
                    <label for="cat_id" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                    <div class="col-md-6">
                        <select id="cat_id" name="cat_id" class="form-control @error('cat_id') is-invalid @enderror" required>
                            <option value="" disabled hidden selected>Select</option>
                            @foreach ($cat as $_cat)
                                <option class="{{ $_cat->type }}" style="display:none" value="{{ $_cat->id }}" {{ old('cat_id') == $_cat->id ? 'selected' : '' }}>{{ $_cat->name }}</option>
                            @endforeach
                        </select>

                        @error('cat_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="wallet_id" class="col-md-4 col-form-label text-md-right">{{ __('Wallet') }}</label>

                    <div class="col-md-6">
                        <select id="wallet_id" name="wallet_id" class="form-control @error('wallet_id') is-invalid @enderror" required>
                            <option value="" disabled hidden selected>Select</option>
                            @foreach ($wallet as $_wallet)
                                <option value="{{ $_wallet->id }}" @if (old('wallet_id')) {{ old('wallet_id') == $_wallet->id ? 'selected' : '' }} @endif>
                                    {{ $_wallet->name }}
                                </option>                            
                            @endforeach
                        </select>

                        @error('wallet_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="balance" class="col-md-4 col-form-label text-md-right">{{ __('Balance') }}</label>

                    <div class="col-md-6">
                        <input type="text" disabled id="balance" class="form-control" value="0">
                    </div>
                </div>

                <div class="form-group row benefit_wallet_block" style="display:none">
                    <label for="benefit_wallet" class="col-md-4 col-form-label text-md-right">{{ __('Benefit Wallet') }}</label>

                    <div class="col-md-6">
                        <select id="benefit_wallet" name="benefit_wallet" class="form-control @error('benefit_wallet') is-invalid @enderror">
                            <option value="" disabled hidden selected>Select</option>
                            @foreach ($wallet as $_wallet)
                                <option value="{{ $_wallet->id }}" @if (old('benefit_wallet')) {{ old('benefit_wallet') == $_wallet->id ? 'selected' : '' }} @endif>
                                    {{ $_wallet->name }}
                                </option>                            
                            @endforeach
                        </select>

                        @error('benefit_wallet')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Create') }}
                        </button>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
@endsection
