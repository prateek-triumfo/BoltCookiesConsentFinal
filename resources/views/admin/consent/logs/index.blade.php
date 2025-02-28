@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Consent Logs</span>
                    <a href="{{ route('admin.consent.logs.export') }}" class="btn btn-sm btn-success">Export CSV</a>
                </div>

                <div class="card-body">
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
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td><code>{{ Str::limit($log->cookie_id, 10) }}</code></td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>{{ $log->consented_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.consent.logs.show', $log) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No consent logs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection