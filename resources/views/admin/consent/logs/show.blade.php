@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Consent Log Details</span>
                        <a href="{{ route('admin.consent.logs.index') }}" class="btn btn-sm btn-outline-secondary">Back to Logs</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Basic Information</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">ID</th>
                                    <td>{{ $log->id }}</td>
                                </tr>
                                <tr>
                                    <th>Cookie ID</th>
                                    <td><code>{{ $log->cookie_id }}</code></td>
                                </tr>
                                <tr>
                                    <th>IP Address</th>
                                    <td>{{ $log->ip_address }}</td>
                                </tr>
                                <tr>
                                    <th>Consented At</th>
                                    <td>{{ $log->consented_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $log->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>User Agent</h5>
                        <div class="p-3 bg-light rounded">
                            <code class="text-break">{{ $log->user_agent }}</code>
                        </div>
                    </div>

                    <div>
                        <h5>Consent Preferences</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($log->consent_data as $category => $status)
                                        <tr>
                                            <td>{{ $category }}</td>
                                            <td>
                                                @if($status)
                                                    <span class="badge bg-success">Accepted</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection