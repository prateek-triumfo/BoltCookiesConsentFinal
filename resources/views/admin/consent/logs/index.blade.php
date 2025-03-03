@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Domains Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Domains</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($domains as $domain)
                            <a href="{{ route('admin.consent.logs.index', ['domain_id' => $domain->id]) }}" 
                               class="list-group-item list-group-item-action {{ request('domain_id') == $domain->id ? 'active' : '' }}">
                                {{ $domain->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Consent Logs Section -->
        <div class="col-md-8">
            @if($selectedDomain)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Consent Logs for {{ $selectedDomain->name }}</h5>
                        <a href="{{ route('admin.consent.logs.export') }}" class="btn btn-sm btn-success">Export CSV</a>
                    </div>

                    <div class="card-body">
                        @if($logs && $logs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cookie ID</th>
                                            <th>IP Address</th>
                                            <th>Consented At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                            <tr>
                                                <td>{{ $log->id }}</td>
                                                <td><code>{{ Str::limit($log->cookie_id, 10) }}</code></td>
                                                <td>{{ $log->ip_address }}</td>
                                                <td>{{ $log->consented_at->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.consent.logs.show', $log) }}" 
                                                       class="btn btn-sm btn-outline-primary">View Details</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center mt-4">
                                    {{ $logs->links() }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">No consent logs found for this domain.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h5 class="text-muted mb-0">Select a domain to view its consent logs</h5>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection