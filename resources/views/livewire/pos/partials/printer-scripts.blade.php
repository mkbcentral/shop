<!-- Thermal Printer Scripts -->
<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2/qz-tray.min.js"></script>
<script src="{{ asset('js/qz-thermal-printer.js') }}" defer></script>

<script>
    // Écouter l'événement d'impression thermique
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-thermal-receipt', (data) => {
            console.log('📄 Données reçues pour impression:', data);

            // Attendre que le script soit chargé
            if (typeof window.thermalPrinter !== 'undefined') {
                window.thermalPrinter.printReceipt(data[0]);
            } else {
                console.error('❌ ThermalPrinter non disponible');
                setTimeout(() => {
                    if (typeof window.thermalPrinter !== 'undefined') {
                        window.thermalPrinter.printReceipt(data[0]);
                    }
                }, 1000);
            }
        });

        // Réinitialiser automatiquement après impression
        Livewire.on('sale-completed', () => {
            console.log('✅ Vente terminée, réinitialisation...');
            setTimeout(() => {
                @this.call('closeReceipt');
            }, 2000); // Attendre 2 secondes pour permettre l'impression
        });
    });
</script>
