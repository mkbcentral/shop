<div x-data="{ showModal: false, isEditing: false }"
     @open-send-modal.window="showModal = true"
     @close-send-modal.window="showModal = false"
     @open-whatsapp.window="window.open($event.detail.url, '_blank')">
<x-slot name="header">
    <x-breadcrumb :items="[
        ['label' => 'Accueil', 'url' => route('dashboard')],
        ['label' => 'Factures', 'url' => route('invoices.index')],
        ['label' => $invoice->invoice_number]
    ]" />
</x-slot>

<div class="space-y-6">

     <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Facture {{ $invoice->invoice_number }}</h1>
            <div class="flex items-center gap-2 mt-1">
                @php
                    $statusLabels = [
                        'draft' => 'Brouillon',
                        'sent' => 'Envoyée',
                        'paid' => 'Payée',
                        'cancelled' => 'Annulée'
                    ];
                    $statusColors = [
                        'draft' => 'bg-gray-100 text-gray-800',
                        'sent' => 'bg-blue-100 text-blue-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800'
                    ];
                @endphp
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                </span>
                @if($invoice->isOverdue())
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        En retard
                    </span>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Menu dropdown pour les actions -->
            <div x-data="{ open: false }" class="relative">
                <x-form.button @click="open = !open" variant="secondary">
                    Actions
                    <svg class="w-4 h-4 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </x-form.button>

                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 z-50 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                >
                    <div class="py-1">
                        <!-- Envoi -->
                        @if($invoice->status !== 'cancelled')
                            <button wire:click="openSendModal('email')" @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <x-icons.mail class="w-4 h-4 mr-3" />
                                Envoyer par Email
                            </button>
                            <button wire:click="openSendModal('whatsapp')" @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Envoyer par WhatsApp
                            </button>
                            <div class="border-t border-gray-100 my-1"></div>
                        @endif

                        <!-- Modifier -->
                        @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                            <a href="{{ route('invoices.edit', $invoice->id) }}" wire:navigate
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" @click="open = false">
                                <x-icons.edit class="w-4 h-4 mr-3" />
                                Modifier
                            </a>
                        @endif

                        <div class="border-t border-gray-100 my-1"></div>

                        <!-- PDF -->
                        <a href="{{ route('invoices.pdf.view', $invoice) }}" target="_blank"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" @click="open = false">
                            <x-icons.eye class="w-4 h-4 mr-3" />
                            Aperçu PDF
                        </a>
                        <a href="{{ route('invoices.pdf', $invoice) }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" @click="open = false">
                            <x-icons.download class="w-4 h-4 mr-3" />
                            Télécharger PDF
                        </a>

                        <!-- Actions de statut -->
                        @if($invoice->status === 'draft')
                            <div class="border-t border-gray-100 my-1"></div>
                            <button wire:click="sendInvoice" @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Marquer comme envoyée
                            </button>
                        @endif

                        @if($invoice->status === 'sent')
                            <div class="border-t border-gray-100 my-1"></div>
                            <button wire:click="markAsPaid" @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-green-600 hover:bg-green-50">
                                <x-icons.check-circle class="w-4 h-4 mr-3" />
                                Marquer comme payée
                            </button>
                        @endif

                        @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                            <div class="border-t border-gray-100 my-1"></div>
                            <button wire:click="cancelInvoice" @click="open = false"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <x-icons.x-circle class="w-4 h-4 mr-3" />
                                Annuler la facture
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <x-form.button href="{{ route('invoices.index') }}" wire:navigate variant="ghost" icon="arrow-left">
                Retour
            </x-form.button>
        </div>
    </div>

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
            <!-- Organization/Company Information -->
            @if($invoice->organization)
                <x-card>
                    <x-slot:header>
                        <x-card-title title="Émetteur de la Facture" />
                    </x-slot:header>

                    <div class="flex items-start gap-4">
                        @if($invoice->organization->logo)
                            <img src="{{ $invoice->organization->logo }}" alt="{{ $invoice->organization->name }}" class="h-16 w-16 object-contain rounded-lg">
                        @else
                            <div class="h-16 w-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 space-y-2">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $invoice->organization->legal_name ?? $invoice->organization->name }}</h3>
                                @if($invoice->organization->legal_name && $invoice->organization->name !== $invoice->organization->legal_name)
                                    <p class="text-sm text-gray-500">{{ $invoice->organization->name }}</p>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                @if($invoice->organization->address)
                                    <div>
                                        <span class="text-gray-500">Adresse:</span>
                                        <p class="text-gray-900">{{ $invoice->organization->address }}</p>
                                        @if($invoice->organization->city)
                                            <p class="text-gray-900">{{ $invoice->organization->city }}, {{ $invoice->organization->country ?? '' }}</p>
                                        @endif
                                    </div>
                                @endif
                                @if($invoice->organization->phone || $invoice->organization->email)
                                    <div>
                                        @if($invoice->organization->phone)
                                            <p class="text-gray-900"><span class="text-gray-500">Tél:</span> {{ $invoice->organization->phone }}</p>
                                        @endif
                                        @if($invoice->organization->email)
                                            <p class="text-gray-900"><span class="text-gray-500">Email:</span> {{ $invoice->organization->email }}</p>
                                        @endif
                                    </div>
                                @endif
                                @if($invoice->organization->tax_id)
                                    <div>
                                        <span class="text-gray-500">N.I.F:</span>
                                        <p class="text-gray-900 font-medium">{{ $invoice->organization->tax_id }}</p>
                                    </div>
                                @endif
                                @if($invoice->organization->registration_number)
                                    <div>
                                        <span class="text-gray-500">RCCM:</span>
                                        <p class="text-gray-900 font-medium">{{ $invoice->organization->registration_number }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-card>
            @endif

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
                                        {{ format_currency($item->unit_price) }}
                                    </span>
                                </x-table.cell>
                                <x-table.cell align="right">
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ format_currency($item->total_price) }}
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
                        <span class="text-gray-900 font-medium">{{ format_currency($invoice->subtotal) }}</span>
                    </div>
                    @if($invoice->tax > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Taxe</span>
                            <span class="text-gray-900 font-medium">{{ format_currency($invoice->tax) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                        <span class="text-gray-900">Total</span>
                        <span class="text-indigo-600">{{ format_currency($invoice->total) }}</span>
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

    <!-- Send Modal (Email / WhatsApp) -->
    <x-ui.alpine-modal name="send" max-width="md"
        :title="$sendMode === 'whatsapp' ? 'Envoyer par WhatsApp' : 'Envoyer par Email'"
        :icon-bg="$sendMode === 'whatsapp' ? 'from-green-500 to-green-600' : 'from-indigo-500 to-indigo-600'">
        <x-slot name="icon">
            @if($sendMode === 'whatsapp')
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            @else
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            @endif
        </x-slot>

        <form wire:submit.prevent="sendInvoiceByMode">
            <x-ui.alpine-modal-body>
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">
                        La facture <strong>{{ $invoice->invoice_number }}</strong> sera envoyée
                        @if($sendMode === 'whatsapp')
                            via WhatsApp avec un lien vers le PDF.
                        @else
                            par email avec le fichier PDF en pièce jointe.
                        @endif
                    </p>

                    <!-- Nom du contact -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nom du destinataire
                            @if(!$invoice->sale->client)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input type="text" wire:model="contactName"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('contactName') @enderror"
                            placeholder="Nom du client">
                        @error('contactName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone (pour WhatsApp) -->
                    @if($sendMode === 'whatsapp')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro WhatsApp <span class="text-red-500">*</span></label>
                            <input type="tel" wire:model="contactPhone"
                                class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('contactPhone') border-red-500 @enderror"
                                placeholder="+243 XXX XXX XXX">
                            @error('contactPhone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Email (pour Email) -->
                    @if($sendMode === 'email')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse email <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="contactEmail"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('contactEmail') @enderror"
                                placeholder="client@exemple.com">
                            @error('contactEmail')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Info -->
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-blue-700">
                                @if($sendMode === 'email')
                                    <span>La facture PDF sera envoyée en pièce jointe.</span>
                                @else
                                    <span>Un lien vers la facture PDF sera envoyé via WhatsApp.</span>
                                @endif
                                @if(!$invoice->sale->client)
                                    <br><span class="font-medium text-green-700">Un nouveau client sera créé avec ces coordonnées.</span>
                                @endif
                                @if($invoice->status === 'draft')
                                    <br><span class="font-medium">Le statut passera à "Envoyée".</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.alpine-modal-body>

            <div class="flex-shrink-0 flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <button type="button" @click="showModal = false"
                    class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Annuler
                </button>
                <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white transition disabled:opacity-50 {{ $sendMode === 'whatsapp' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500' : 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                    <svg wire:loading.remove wire:target="sendInvoiceByMode" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <svg wire:loading wire:target="sendInvoiceByMode" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="sendInvoiceByMode">{{ $sendMode === 'whatsapp' ? 'Ouvrir WhatsApp' : 'Envoyer' }}</span>
                    <span wire:loading wire:target="sendInvoiceByMode">Envoi...</span>
                </button>
            </div>
        </form>
    </x-ui.alpine-modal>

</div>
</div>
