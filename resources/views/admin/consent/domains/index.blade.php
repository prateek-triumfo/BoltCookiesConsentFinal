@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Domains</span>
                    <a href="{{ route('admin.consent.domains.create') }}" class="btn btn-sm btn-primary">Add New Domain</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($domains as $domain)
                                    <tr>
                                        <td>{{ $domain->id }}</td>
                                        <td>{{ $domain->name }}</td>
                                        <td>{{ Str::limit($domain->description, 50) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.consent.domains.edit', $domain) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <form action="{{ route('admin.consent.domains.destroy', $domain) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this domain?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No domains found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
