@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="css/category.css">
@endpush

@push('script')
    <script>
        function delCat(id) {
        var name = $("#cat_" + id).val();
        if (confirm("Are you sure to delete " + name + " and it's sub categories?")) {
            $("#frmCatDel_" + id).submit();
        }
    }
    </script>
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
        <div class="card-header row">
            <div class="col-lg-2 p-0 d-flex justify-content-start">
                <a href="/dashboard" class="btn btn-success mt-auto mb-auto">Back</a>
            </div>
            <div class="col-lg-8 d-flex justify-content-center">
                <h4 class="mt-auto mb-auto">Categories</h4>
            </div>
        </div>
        <div class="card-body">
            {!! $menu !!}
        </div>
    </div>
</div>
@endsection
