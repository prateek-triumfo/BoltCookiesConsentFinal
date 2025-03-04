@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Admin Dashboard</h1>

            <div class="row">
                <!-- Banner Settings Card -->
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Banner Settings</h5>
                            <p class="card-text">Customize the consent banner appearance and behavior.</p>
                            <a href="{{ route('admin.banner.edit') }}" class="btn btn-primary">Manage Banner</a>
                        </div>
                    </div>
                </div>

                <!-- Consent Categories Card -->
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Consent Categories</h5>
                            <p class="card-text">Manage consent categories and their settings.</p>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Manage Categories</a>
                        </div>
                    </div>
                </div>

                <!-- Domains Card -->
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Domains</h5>
                            <p class="card-text">Manage domains and their configurations.</p>
                            <a href="{{ route('admin.domains.index') }}" class="btn btn-primary">Manage Domains</a>
                        </div>
                    </div>
                </div>

                <!-- Consent Logs Card -->
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Consent Logs</h5>
                            <p class="card-text">View and export user consent logs.</p>
                            <a href="{{ route('admin.consent.logs.index') }}" class="btn btn-primary">View Logs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 