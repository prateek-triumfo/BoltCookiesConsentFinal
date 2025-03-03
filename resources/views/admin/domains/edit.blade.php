@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Domain</h2>

    <form action="{{ route('admin.consent.domains.update', $domain) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Domain Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $domain->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $domain->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-warning">Update</button>
    </form>
</div>
@endsection
