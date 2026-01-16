<!-- Alpine.js Initialization Script -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cashRegister', () => ({
        showClientModal: false,
        showStatsPanel: false,
        barcodeBuffer: '',
        barcodeTimeout: null,

        init() {
            this.initClock();
            this.initKeyboardShortcuts();
            this.initBarcodeScanner();
            this.initLivewireListeners();
        },

        initClock() {
            setInterval(() => {
                if (this.$refs.clock) {
                    this.$refs.clock.textContent = new Date().toLocaleTimeString('fr-FR', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }
            }, 1000);
        },

        initKeyboardShortcuts() {
            window.addEventListener('keydown', (e) => {
                // F9 - Valider la vente
                if (e.key === 'F9') {
                    e.preventDefault();
                    this.$wire.call('keyboardValidateSale');
                }
                // F4 - Vider le panier
                if (e.key === 'F4') {
                    e.preventDefault();
                    if (confirm('Vider le panier?')) {
                        this.$wire.call('clearCart');
                    }
                }
                // F2 - Focus recherche
                if (e.key === 'F2') {
                    e.preventDefault();
                    document.querySelector('input[wire\\:model\\.live\\.debounce\\.300ms=search]')?.focus();
                }
                // Esc - Fermer modaux
                if (e.key === 'Escape') {
                    this.showClientModal = false;
                    this.showStatsPanel = false;
                }
            });
        },

        initBarcodeScanner() {
            window.addEventListener('keypress', (e) => {
                if (document.activeElement.tagName !== 'INPUT' ||
                    document.activeElement.getAttribute('wire:model.live.debounce.300ms') === 'search') {
                    clearTimeout(this.barcodeTimeout);
                    this.barcodeBuffer += e.key;

                    this.barcodeTimeout = setTimeout(() => {
                        if (this.barcodeBuffer.length > 3) {
                            this.$wire.set('barcodeInput', this.barcodeBuffer);
                            this.$wire.call('handleBarcodeScan');
                        }
                        this.barcodeBuffer = '';
                    }, 100);
                }
            });
        },

        initLivewireListeners() {
            window.addEventListener('focus-search', () => {
                document.querySelector('input[wire\\:model\\.live\\.debounce\\.300ms=search]')?.focus();
            });
        }
    }));
});
</script>
