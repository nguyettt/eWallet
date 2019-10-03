@extends('layouts.app')

{{-- @push('script')
<script src="js/category.js"></script>
@endpush --}}

@section('title')
    Edit {{ $cat->name }}
@endsection

@section('content')
<div class="container">
    <div class="card bg-light p-5 col-lg-8 offset-lg-2">
        <div class="card-header row">
            <div class="col-lg-2 p-0 d-flex justify-content-start">
                <a href="/cat" class="btn btn-success mt-auto mb-auto">Back</a>
            </div>
            <div class="col-lg-8 d-flex justify-content-center">
                <h5 class="font-weight-bold mt-auto mb-auto">{{ __('Edit Category') }}</h5>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="cat/{{ $cat->id }}">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Category Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror @error('existed') is-invalid @enderror"
                            name="name" value="{{ (old('name')) ? old('name') : $cat->name }}" required autocomplete="name" autofocus>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        @error('existed')
                        <span class="invalid-feedback" role="alert">
                            <strong>There is one category with this name that has been deleted but still has transaction(s) links to it, please choose another name</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="parent_id" class="col-md-4 col-form-label text-md-right">{{ __('Parent category') }}</label>

                    <div class="col-md-6">
                        <select id="parent_id" name="parent_id" class="form-control @error('parent_id') is-invalid @enderror" required>
                            <option value="" disabled hidden selected>Select</option>
                            @foreach ($cats as $_cat)
                                <option 
                                    value="{{ $_cat->id }}" {{ (old('parent_id')) ? (old('parent_id') == $_cat->id ? 'selected' : '') : ($cat->parent_id == $_cat->id ? 'selected' : '') }}>{{ $_cat->name }}
                                </option>
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
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
@endsection
