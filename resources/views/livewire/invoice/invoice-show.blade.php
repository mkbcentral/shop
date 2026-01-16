<div>
<x-slot name="header">
    <x-breadcrumb :items="[
        ['label' => 'Accueil', 'url' => route('dashboard')],
        ['label' => 'Factures', 'url' => route('invoices.index')],
        ['label' => $invoice->invoice_number]
    ]" />

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Facture {{ $invoice->invoice_number }}</h1>
            <p class="text-gray-500 mt-1">
                @php
                    $statusLabels = [
                        'draft' => 'Brouillon',
                        'sent' => 'Envoyée',
                        'paid' => 'Payée',
                        'cancelled' => 'Annulée'
                    ];
                @endphp
                Statut: {{ $statusLabels[$invoice->status] ?? $invoice->status }}
            </p>
        </div>
        <div class="flex items-center space-x-3">
            @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                <x-form.button href="{{ route('invoices.edit', $invoice->id) }}" wire:navigate icon="edit">
                    Modifier
                </x-form.button>
            @endif
        </div>
    </div>
</x-slot>

<div class="space-y-6">

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="mb-4" wire:key="success-{{ now() }}">
            <x-form.alert type="success" :message="session('success')" />
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4" wire:key="error-{{ now() }}">
            <x-form.alert type="error" :message="session('error')" />
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Header -->
            <x-card>
                <x-slot:header>
                    <x-card-title title="Informations de la Facture" />
                </x-slot:header>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Numéro de facture</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Statut</h3>
                        @php
                            $statusColors = [
                                'draft' => 'gray',
                                'sent' => 'blue',
                                'paid' => 'green',
                                'cancelled' => 'red'
                            ];
                            $statusLabels = [
                                'draft' => 'Brouillon',
                                'sent' => 'Envoyée',
                                'paid' => 'Payée',
                                'cancelled' => 'Annulée'
                            ];
                        @endphp
                        <x-table.badge :color="$statusColors[$invoice->status] ?? 'gray'" dot>
                            {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                        </x-table.badge>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Date de facturation</h3>
                        <p class="text-base text-gray-900">{{ $invoice->invoice_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Date d'échéance</h3>
                        <p class="text-base text-gray-900">
                            @if($invoice->due_date)
                                {{ $invoice->due_date->format('d/m/Y') }}
                                @if($invoice->isOverdue())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        En retard
                                    </span>
                                @endif
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </x-card>

            <!-- Client Information -->
            <x-card>
                <x-slot:header>
                    <x-card-title title="Informations Client" />
                </x-slot:header>

                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Nom</h3>
                        <p class="text-base text-gray-900">{{ $invoice->sale->client->name ?? 'Client Walk-in' }}</p>
                    </div>
                    @if($invoice->sale->client)
                        @if($invoice->sale->client->email)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                                <p class="text-base text-gray-900">{{ $invoice->sale->client->email }}</p>
                            </div>
                        @endif
                        @if($invoice->sale->client->phone)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Téléphone</h3>
                                <p class="text-base text-gray-900">{{ $invoice->sale->client->phone }}</p>
                            </div>
                        @endif
                        @if($invoice->sale->client->address)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Adresse</h3>
                                <p class="text-base text-gray-900">{{ $invoice->sale->client->address }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </x-card>

            <!-- Sale Items -->
            <x-card>
                <x-slot:header>
                    <x-card-title title="Articles" />
                </x-slot:header>

                <x-table.table>
                    <x-table.head>
                        <tr>
                            <x-table.header>Produit</x-table.header>
                            <x-table.header align="center">Quantité</x-table.header>
                            <x-table.header align="right">Prix unitaire</x-table.header>
                            <x-table.header align="right">Total</x-table.header>
                        </tr>
                    </x-table.head>
                    <x-table.body>
                        @foreach($invoice->sale->items as $item)
                            <x-table.row>
                                <x-table.cell>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->productVariant->product->name }}
                                    </div>
                                    @if($item->productVariant->size || $item->productVariant->color)
                                        <div class="text-xs text-gray-500">
                                            {{ $item->productVariant->size }} {{ $item->productVariant->color }}
                                        </div>
                                    @endif
                                </x-table.cell>
                                <x-table.cell align="center">
                                    <span class="text-sm text-gray-900">{{ $item->quantity }}</span>
                                </x-table.cell>
                                <x-table.cell align="right">
                                    <span class="text-sm text-gray-900">
                                        {{ number_format($item->unit_price, 0, ',', ' ') }} CDF
                                    </span>
                                </x-table.cell>
                                <x-table.cell align="right">
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ number_format($item->total_price, 0, ',', ' ') }} CDF
                                    </span>
                                </x-table.cell>
                            </x-table.row>
                        @endforeach
                    </x-table.body>
                </x-table.table>

                <!-- Totals -->
                <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Sous-total</span>
                        <span class="text-gray-900 font-medium">{{ number_format($invoice->subtotal, 0, ',', ' ') }} CDF</span>
                    </div>
                    @if($invoice->tax > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Taxe</span>
                            <span class="text-gray-900 font-medium">{{ number_format($invoice->tax, 0, ',', ' ') }} CDF</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                        <span class="text-gray-900">Total</span>
                        <span class="text-indigo-600">{{ number_format($invoice->total, 0, ',', ' ') }} CDF</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Sale Reference -->
            <x-card>
                <x-slot:header>
                    <x-card-title title="Vente Associée" />
                </x-slot:header>

                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Numéro de vente</h3>
                        <p class="text-base text-indigo-600 font-semibold">
                            {{ $invoice->sale->sale_number }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Date de vente</h3>
                        <p class="text-base text-gray-900">{{ $invoice->sale->sale_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Statut de paiement</h3>
                        @php
                            $paymentStatusColors = [
                                'pending' => 'orange',
                                'partial' => 'yellow',
                                'paid' => 'green'
                            ];
                            $paymentStatusLabels = [
                                'pending' => 'En attente',
                                'partial' => 'Partiel',
                                'paid' => 'Payé'
                            ];
                        @endphp
                        <x-table.badge :color="$paymentStatusColors[$invoice->sale->payment_status] ?? 'gray'">
                            {{ $paymentStatusLabels[$invoice->sale->payment_status] ?? $invoice->sale->payment_status }}
                        </x-table.badge>
                    </div>
                </div>
            </x-card>

            <!-- Actions -->
            @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                <x-card>
                    <x-slot:header>
                        <x-card-title title="Actions" />
                    </x-slot:header>

                    <div class="space-y-2">
                        @if($invoice->status === 'draft')
                            <button wire:click="sendInvoice" wire:loading.attr="disabled"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition duration-150 disabled:opacity-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span wire:loading.remove wire:target="sendInvoice">Envoyer la facture</span>
                                <span wire:loading wire:target="sendInvoice">Envoi en cours...</span>
                            </button>
                        @endif

                        @if($invoice->status === 'sent')
                            <button wire:click="markAsPaid" wire:loading.attr="disabled"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-sm transition duration-150 disabled:opacity-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span wire:loading.remove wire:target="markAsPaid">Marquer comme payée</span>
                                <span wire:loading wire:target="markAsPaid">Traitement...</span>
                            </button>
                        @endif

                        <button wire:click="cancelInvoice" wire:loading.attr="disabled"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg shadow-sm transition duration-150 disabled:opacity-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span wire:loading.remove wire:target="cancelInvoice">Annuler la facture</span>
                            <span wire:loading wire:target="cancelInvoice">Annulation...</span>
                        </button>
                    </div>
                </x-card>
            @endif

            <!-- Timestamps -->
            <x-card>
                <x-slot:header>
                    <x-card-title title="Informations Système" />
                </x-slot:header>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Créée le:</span>
                        <span class="text-gray-900">{{ $invoice->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Modifiée le:</span>
                        <span class="text-gray-900">{{ $invoice->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

</div>
</div>
