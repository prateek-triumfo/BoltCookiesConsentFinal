@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage Domains</h5>
                    <a href="{{ route('admin.domains.create') }}" class="btn btn-primary btn-sm">
                        Add New Domain
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Status</th>
                                    <th>Consent Count</th>
                                    <th>Last Used</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($domains as $domain)
                                    <tr>
                                        <td>{{ $domain->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $domain->is_active ? 'success' : 'danger' }}">
                                                {{ $domain->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $domain->consent_count }}</td>
                                        <td>{{ $domain->last_used_at ? $domain->last_used_at->format('Y-m-d H:i:s') : 'Never' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.domains.edit', $domain) }}" class="btn btn-sm btn-info">
                                                    Edit
                                                </a>
                                                <a href="{{ route('admin.banner-settings.edit', $domain) }}" class="btn btn-sm btn-primary">
                                                    Banner Settings
                                                </a>
                                                <a href="{{ route('admin.domains.embed-code', $domain) }}" class="btn btn-sm btn-secondary">
                                                    Embed Code
                                                </a>
                                                <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this domain?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No domains found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $domains->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection