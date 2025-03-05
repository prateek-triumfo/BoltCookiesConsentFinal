@extends('layouts.admin')

@section('content')
    <h1>Edit Banner Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.banner.update', $domain) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Add form fields for banner settings here -->
        <div class="form-group">
            <label for="banner_title">Banner Title</label>
            <input type="text" name="banner_title" id="banner_title" value="{{ old('banner_title', $bannerSetting->banner_title) }}" class="form-control">
            @error('banner_title')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <!-- Repeat similar blocks for other fields -->

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
@endsection
