@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="css/category.css">
@endpush

@push('script')
    <script src="js/category.js"></script>
    @error('delete')
        <script>
            $(function () {
                alert("Can not delete default category");
            })
        </script>
    @enderror

    @error('edit')
        <script>
            $(function () {
                alert("Can not edit dafault category");
            })
        </script>
    @enderror
@endpush

@section('title')
    Categories
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row justify-content-center">
            <h4>Categories</h4>
        </div>
        <div class="card-body">
            {!! $menu !!}
        </div>
    </div>
</div>
@endsection
