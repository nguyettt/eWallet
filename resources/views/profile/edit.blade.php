@extends('layouts.app')

@section('title')
    Edit profile
@endsection

@push('script')
<script>
    var loadImg = function (event) {
        $("#avatar").attr("src", URL.createObjectURL(event.target.files[0]));
    }
</script>
@endpush

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row justify-content-center">
            <h5 class="font-weight-bold">{{ __('Edit Profile') }}</h5>
        </div>

        <div class="card-body">
            <div class="row pb-2 justify-content-lg-center">
                <img id="avatar" src="{{ $user->avatar }}" alt="..." width="100"
                class="mr-3 rounded-circle img-thumbnail shadow-sm">
            </div>

            <form method="POST" action="/profile/edit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                    <div class="col-md-6">
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') ?? $user->username }}" required autocomplete="username" autofocus>

                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') ?? $user->email }}" required autocomplete="email">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password_current" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

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
                    <label for="firstName" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                    <div class="col-md-6">
                        <input id="firstName" type="text" class="form-control @error('firstName') is-invalid @enderror"
                            name="firstName" required value="{{ old('firstName') ?? $user->firstName }}" autocomplete="firstName">

                        @error('firstName')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lastName" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                    <div class="col-md-6">
                        <input id="lastName" type="text" class="form-control @error('lastName') is-invalid @enderror"
                            name="lastName" value="{{ old('lastName') ?? $user->lastName }}" required autocomplete="lastName">

                        @error('lastName')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Birthday') }}</label>

                    <div class="col-md-6">
                        <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob"
                            required value="{{ old('dob') ?? $user->dob }}" autocomplete="dob">

                        @error('dob')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                    <div class="col-md-6">
                        {{-- <input id="gender" type="text" class="form-control @error('lastName') is-invalid @enderror" name="lastName" required autocomplete="lastName"> --}}
                        <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror"
                            value="{{ old('gender') }}" required>
                            <option value="" disabled hidden selected>Select</option>
                            <option value="Male" {{ (old('gender')) ? (old('gender')=='Male' ? 'selected' : '') : ($user->gender == 'Male' ? 'selected' : '') }}>Male</option>
                            <option value="Female" {{ (old('gender')) ? (old('gender')=='Female' ? 'selected' : '') : ($user->gender == 'Female' ? 'selected' : '') }}>Female</option>
                        </select>

                        @error('gender')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Avatar') }}</label>

                    <div class="col-md-6">
                        {{-- <input id="gender" type="text" class="form-control @error('lastName') is-invalid @enderror" name="lastName" required autocomplete="lastName"> --}}
                        <input type="file" accept="image/*" id="file" name="file" class="form-control-file @error('file') is-invalid @enderror" onchange="loadImg(event)">

                        @error('file')
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
</div>
@endsection
