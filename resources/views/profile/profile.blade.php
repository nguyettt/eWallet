@extends('layouts.app')

@section('title')
    Profile
@endsection

@push('script')
    <script>
        function deleteAccount () {
            if (confirm('Are you sure to delete your account?')) {
                $("#frmDelete").submit();
            }
        }
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="card bg-light p-5 col-lg-8 offset-lg-2">
            <div class="row justify-content-lg-center mb-5">
                <div class="col-lg-2">
                    <img class="img-thumbnail rounded-circle" src="{{ $user->avatar }}">
                </div>
            </div>
            <div class="row justify-content-lg-center">
                <div class="col-lg-6"><p class="float-right font-weight-bold">Username:</p></div>
                <div class="col-lg-6 justify-content-start">{{ $user->username }}</div>
            </div>
            <div class="row justify-content-lg-center">
                <div class="col-lg-6"><p class="float-right font-weight-bold">Email:</p></div>
                <div class="col-lg-6 justify-content-start">{{ $user->email }}</div>
            </div>
            <div class="row justify-content-lg-center">
                <div class="col-lg-6"><p class="float-right font-weight-bold">Gender:</p></div>
                <div class="col-lg-6 justify-content-start">{{ $user->gender }}</div>
            </div>
            <div class="row justify-content-lg-center">
                <div class="col-lg-6"><p class="float-right font-weight-bold">Birthday:</p></div>
                <div class="col-lg-6 justify-content-start">{{ $user->dob }}</div>
            </div>
            <div class="row justify-content-lg-center">
                <div class="col-lg-6"><p class="float-right font-weight-bold">First name:</p></div>
                <div class="col-lg-6 justify-content-start">{{ $user->firstName }}</div>
            </div>
            <div class="row justify-content-lg-center">
                <div class="col-lg-6"><p class="float-right font-weight-bold">Last name:</p></div>
                <div class="col-lg-6 justify-content-start">{{ $user->lastName }}</div>
            </div>
            <div class="row justify-content-lg-center">
                <div class="col-lg-6"><p class="float-right font-weight-bold">Created at:</p></div>
                <div class="col-lg-6 justify-content-start">{{ $user->created_at }}</div>
            </div>
            <div class="row justify-content-lg-center">
                <div><a href="profile/edit" class="btn btn-primary">Edit Profile</a></div>
            </div>
            <div class="row justify-content-lg-center mt-3">
                <div><a href="profile/password" class="btn btn-primary">Change Password</a></div>
            </div>
            <div class="row justify-content-lg-center mt-3">
                <form method="POST" action="profile" id="frmDelete">
                    @csrf
                    @method('DELETE')
                    <input type="button" class="btn btn-danger" value="Delete" id="delete" onclick="deleteAccount()"></input>
                </div>
            </div>
        </div>
    </div>
@endsection
