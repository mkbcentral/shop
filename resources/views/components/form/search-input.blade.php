@props([
    'placeholder' => 'Rechercher...',
    'wireModel' => null,
    'wireTarget' => null,
])

@php
    $target = $wireTarget ?? $wireModel ?? 'search';
@endphp

<div class="relative">
    <!-- Search Icon / Loading Spinner -->
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg wire:loading.remove wire:target="{{ $target }}" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <svg wire:loading wire:target="{{ $target }}" class="h-5 w-5 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <!-- Input -->
    <input
        type="text"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        {{ $attributes->merge(['class' => 'block w-full pl-10 pr-10 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150']) }}
    >

    <!-- Clear Button -->
    @if($wireModel)
        <button
            x-data="{ value: $wire.entangle('{{ $wireModel }}') }"
            x-show="value && value.length > 0"
            x-cloak
            wire:click="$set('{{ $wireModel }}', '')"
            type="button"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
</div>
