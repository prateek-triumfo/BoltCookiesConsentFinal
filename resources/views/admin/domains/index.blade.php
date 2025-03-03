@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Domains</h2>
    <a href="{{ route('admin.consent.domains.create') }}" class="btn btn-primary">Create Domain</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($domains as $domain)
                <tr>
                    <td>{{ $domain->id }}</td>
                    <td>{{ $domain->name }}</td>
                    <td>{{ $domain->description }}</td>
                    <td>
                        <a href="{{ route('admin.consent.domains.edit', $domain) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.consent.domains.destroy', $domain) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
