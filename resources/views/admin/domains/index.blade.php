@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Domains</h5>
                    <a href="{{ route('admin.domains.create') }}" class="btn btn-primary">Add New Domain</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
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
                                        <span class="badge bg-{{ $domain->status ? 'success' : 'danger' }}">
                                            {{ $domain->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $domain->consent_count }}</td>
                                    <td>{{ $domain->last_used_at ? $domain->last_used_at->format('Y-m-d H:i') : 'Never' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.domains.edit', $domain) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit Domain
                                            </a>
                                            <a href="{{ route('admin.banner-settings.edit', $domain) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-cog"></i> Banner Settings
                                            </a>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#embedCodeModal{{ $domain->id }}">
                                                <i class="fas fa-code"></i> Embed Code
                                            </button>
                                            <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this domain?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Embed Code Modal -->
                                <div class="modal fade" id="embedCodeModal{{ $domain->id }}" tabindex="-1" aria-labelledby="embedCodeModalLabel{{ $domain->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="embedCodeModalLabel{{ $domain->id }}">Embed Code for {{ $domain->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Copy this code and paste it into your website's HTML:</label>
                                                    <div class="input-group">
                                                        <textarea class="form-control" id="embedCode{{ $domain->id }}" rows="6" readonly>{{ $domain->getEmbedCode() }}</textarea>
                                                        <button class="btn btn-primary" onclick="copyEmbedCode({{ $domain->id }})">
                                                            <i class="fas fa-copy"></i> Copy
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i> Place this code just before the closing </body> tag of your website.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

@push('scripts')
<script>
function copyEmbedCode(domainId) {
    const embedCode = document.getElementById('embedCode' + domainId);
    embedCode.select();
    document.execCommand('copy');
    
    // Show feedback
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
    setTimeout(() => {
        button.innerHTML = originalText;
    }, 2000);
}
</script>
@endpush

@endsection