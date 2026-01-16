@props(['chartData'])

<x-card>
    <x-slot:header>
        <x-card-title title="Ventes (7 derniers jours)">
            <x-slot:action>
                <a href="{{ route('sales.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Voir tout →</a>
            </x-slot:action>
        </x-card-title>
    </x-slot:header>

    <div class="p-4">
        <canvas id="salesChart" height="250"></canvas>
    </div>

    @if($chartData->sum('total') == 0)
        <p class="text-center text-sm text-gray-500 pb-4">Aucune vente enregistrée ces 7 derniers jours</p>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart');
            if (!ctx) {
                console.error('Canvas salesChart introuvable');
                return;
            }

            const salesData = @json($chartData);
            const currency = '{{ current_currency() }}';

            const labels = salesData.map(item => {
                const date = new Date(item.day);
                return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
            });

            const data = salesData.map(item => item.total);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventes (' + currency + ')',
                        data: data,
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: 'rgba(79, 70, 229, 0.9)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' ' + currency;
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR', {
                                        notation: 'compact',
                                        compactDisplay: 'short'
                                    }).format(value);
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-card>
