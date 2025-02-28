@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Consent Category</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.consent.categories.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="key" class="form-label">Key</label>
                            <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key') }}" required>
                            <div class="form-text">Unique identifier for this category (e.g., "analytics", "marketing")</div>
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_required" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_required">Required</label>
                            <div class="form-text">If checked, users cannot disable this category</div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                            <div class="form-text">If unchecked, this category will not be shown to users</div>
                        </div>

                        <div class="mb-3">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old(' display_order', '0') }}" min="0">
                            <div class="form-text">Categories are displayed in ascending order</div>
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.consent.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection