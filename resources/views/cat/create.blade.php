@extends('layouts.app')

@push('script')
<script>
var data = {!! $data !!};
</script>
<script src="js/category.js"></script>
@if ($errors->has('restore'))
    <script>
        $(function () {
            if (confirm('There is 1 deleted category with this name, do you want to restore it instead?')) {
                window.location.href = "/cat/{{ session('restore_id') }}/restore";
            }
        })
    </script>
@endif
@endpush

@section('title')
    Create new category
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row">
            <div class="col-lg-2 p-0 d-flex justify-content-start">
                <a href="/cat" class="btn btn-success mt-auto mb-auto">Back</a>
            </div>
            <div class="col-lg-8 d-flex justify-content-center">
                <h5 class="font-weight-bold mt-auto mb-auto">{{ __('New Category') }}</h5>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="cat">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Category Name') }}</label>

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

                <div class="form-group row">
                    <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                    <div class="col-md-6">
                        <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                            <option value="" disabled hidden selected>Select</option>
                            <option value="{{ config('variable.type.income') }}" {{ old('type') == config('variable.type.income') ? 'selected' : '' }}>Income</option>
                            <option value="{{ config('variable.type.outcome') }}" {{ old('type') == config('variable.type.outcome') ? 'selected' : '' }}>Expense</option>
                        </select>

                        @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="parent_id" class="col-md-4 col-form-label text-md-right">{{ __('Parent category') }}</label>

                    <div class="col-md-6">
                        <select id="parent_id" name="parent_id" class="form-control @error('parent_id') is-invalid @enderror" required>
                            <option value="" disabled hidden selected>Select</option>
                            @foreach ($cat as $_cat)
                                <option class="{{ $_cat->type }}" style="display:none" value="{{ $_cat->id }}" {{ old('parent_id') == $_cat->id ? 'selected' : '' }}>{{ $_cat->name }}</option>
                            @endforeach
                        </select>

                        @error('parent_id')
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
