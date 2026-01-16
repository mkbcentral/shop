<x-layouts.app>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Administration'],
            ['label' => 'Gestion des menus']
        ]" />
    </x-slot>

    <div class="flex items-center justify-between mt-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Accès aux Menus</h1>
            <p class="text-gray-500 mt-1">Définissez quels menus sont accessibles pour chaque rôle d'utilisateur</p>
        </div>
    </div>

    <livewire:admin.menu-permission-manager />
</x-layouts.app>
