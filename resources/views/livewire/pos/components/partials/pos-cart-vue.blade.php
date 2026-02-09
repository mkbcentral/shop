<div class="w-full bg-white flex flex-col overflow-hidden h-full">

    <!-- Vue.js Cart Component -->
    <div id="vue-pos-cart" class="flex-1 overflow-hidden flex flex-col"
        data-clients="{{ json_encode($clients) }}"
        data-currency="{{ current_currency() }}"
        data-taxes="{{ json_encode($taxes ?? []) }}"
        data-has-taxes="{{ $hasTaxes ? 'true' : 'false' }}">
    </div>
</div>

@push('scripts')
<script>
    // Toast notifications handler
    window.addEventListener('show-toast', (event) => {
        const { type, message } = event.detail;

        // Use Alpine toast if available
        if (window.Alpine && Alpine.store('toast')) {
            if (type === 'success') Alpine.store('toast').success(message);
            else if (type === 'error') Alpine.store('toast').error(message);
            else Alpine.store('toast').info(message);
        } else {
            console.log(`[Toast ${type}]`, message);
        }
    });
</script>
@endpush
