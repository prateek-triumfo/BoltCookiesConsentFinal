@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Manage Cookie Preferences</div>

                <div class="card-body">
                    <p class="mb-4">
                        You can customize your cookie preferences below. Required cookies are necessary for the website to function properly and cannot be disabled.
                    </p>

                    <form id="consent-form">
                        @foreach($categories as $category)
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="form-check">
                                    <input class="form-check-input consent-checkbox" 
                                           type="checkbox" 
                                           value="1" 
                                           id="consent-{{ $category->key }}" 
                                           name="consent[{{ $category->key }}]" 
                                           {{ $category->is_required ? 'checked disabled' : '' }}>
                                    <label class="form-check-label" for="consent-{{ $category->key }}">
                                        <strong>{{ $category->name }}</strong>
                                        @if($category->is_required)
                                            <span class="badge bg-secondary">Required</span>
                                        @endif
                                    </label>
                                </div>
                                <p class="text-muted mt-2">{{ $category->description }}</p>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="reject-all">Reject All</button>
                            <button type="button" class="btn btn-primary" id="accept-all">Accept All</button>
                            <button type="button" class="btn btn-success" id="save-preferences">Save Preferences</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const consentForm = document.getElementById('consent-form');
        const rejectAllBtn = document.getElementById('reject-all');
        const acceptAllBtn = document.getElementById('accept-all');
        const savePreferencesBtn = document.getElementById('save-preferences');
        const consentCheckboxes = document.querySelectorAll('.consent-checkbox');
        
        // Function to update checkboxes based on preferences
        function updateCheckboxes(preferences) {
            if (!preferences) return;
            
            consentCheckboxes.forEach(checkbox => {
                const key = checkbox.name.match(/\[(.*?)\]/)[1];
                if (!checkbox.disabled && preferences[key] !== undefined) {
                    checkbox.checked = preferences[key];
                }
            });
        }
        
        // Load current preferences
        fetch('{{ route('consent.preferences') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Received preferences:', data);
                if (data.consented && data.preferences) {
                    updateCheckboxes(data.preferences);
                }
            })
            .catch(error => {
                console.error('Error loading preferences:', error);
            });
        
        // Reject All button
        rejectAllBtn.addEventListener('click', function() {
            consentCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = false;
                }
            });
        });
        
        // Accept All button
        acceptAllBtn.addEventListener('click', function() {
            consentCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        });
        
        // Save Preferences button
        savePreferencesBtn.addEventListener('click', function() {
            const consentData = {};
            
            consentCheckboxes.forEach(checkbox => {
                const key = checkbox.name.match(/\[(.*?)\]/)[1];
                consentData[key] = checkbox.checked;
            });
            
            console.log('Saving preferences:', consentData);
            
            fetch('{{ route('consent.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ consent: consentData })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Save response:', data);
                if (data.message === 'Consent preferences saved successfully') {
                    // Update checkboxes with the returned preferences
                    updateCheckboxes(data.preferences);
                    alert('Your preferences have been saved successfully.');
                    // Redirect back to the previous page
                    window.history.back();
                }
            })
            .catch(error => {
                console.error('Error saving preferences:', error);
                alert('There was an error saving your preferences. Please try again.');
            });
        });
    });
</script>
@endpush
@endsection