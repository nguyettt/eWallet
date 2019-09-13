@extends('layouts.app')

@section('title')
    Change Password
@endsection

@section('content')
    <div class="container">
        <div class="card bg-light p-5 col-lg-8 offset-lg-2">
            <form method="POST" action="/profile/password">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label for="password_current" class="col-md-4 col-form-label text-md-right">{{ __('Current Password') }}</label>

                    <div class="col-md-6">
                        <input id="password_current" type="password" class="form-control @error('password_current') is-invalid @enderror"
                            name="password_current" required autocomplete="current_password">

                        @error('password_current')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Password Confirmation') }}</label>

                    <div class="col-md-6">
                        <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" required autocomplete="password_confirmation">

                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
