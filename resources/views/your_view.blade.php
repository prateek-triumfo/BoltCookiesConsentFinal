<!-- BoltConsent Banner -->
<script>
    window.BOLT_CONSENT_CONFIG = {
        scriptId: "{{ $domain->script_id }}",
        apiKey: "{{ $domain->api_key }}"
    };
</script>
<script id="{{ $domain->script_id }}" src="{{ config('app.url') }}/consent/embed.js"></script>
