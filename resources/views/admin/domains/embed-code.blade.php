@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Embed Code for {{ $domain->name }}</div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <p>Copy the code below and paste it into the <code>&lt;head&gt;</code> section of your website's HTML.</p>
                    </div>

                    <div class="form-group">
                        <label for="embedCode">Embed Code:</label>
                        <pre id="embedCode" class="bg-light p-3 rounded" style="white-space: pre-wrap; word-wrap: break-word;">{{ $domain->getEmbedCode() }}</pre>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" onclick="copyEmbedCode()">
                            Copy Code
                        </button>
                        <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary">
                            Back to Domains
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyEmbedCode() {
    const embedCode = document.getElementById('embedCode').textContent;
    navigator.clipboard.writeText(embedCode).then(() => {
        alert('Embed code copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy text: ', err);
        alert('Failed to copy embed code. Please try again.');
    });
}
</script>
@endpush
@endsection 