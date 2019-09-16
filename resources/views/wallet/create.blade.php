@extends('layouts.app')

@section('title')
    Create new wallet
@endsection

@section('content')
    <div class="container">
        <div class="card bg-light p-5 col-lg-8 offset-lg-2">
            <div class="card-header row justify-content-center">
                <h5 class="font-weight-bold">{{ __('New Wallet') }}</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="/wallet">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Wallet name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
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
