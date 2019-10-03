@extends('layouts.app')

@error('restore')
@push('script')
    <script>
        $(function () {
            if (confirm("There is a deleted wallet with the same name, do you want to restore it instead?")) {
                window.location.href = "/wallet/{{ session('restore_id') }}/restore";
            }
        })
    </script>    
@endpush
@enderror

@section('title')
    Create new wallet
@endsection

@section('content')
    <div class="container">
        <div class="card bg-light p-5 col-lg-8 offset-lg-2">
            <div class="card-header row">
                <div class="col-lg-2 p-0 d-flex justify-content-start">
                    <a href="/dashboard" class="btn btn-success mt-auto mb-auto">Back</a>
                </div>
                <div class="col-lg-8 d-flex justify-content-center">
                    <h5 class="font-weight-bold mt-auto mb-auto">{{ __('New Wallet') }}</h5>
                </div>
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
