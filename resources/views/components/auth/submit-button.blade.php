@props([
    'loading' => false,
    'disabled' => false,
    'loadingTarget' => 'login',
    'text' => 'Soumettre',
    'loadingText' => 'Chargement...',
    'lockedText' => 'Bloqué',
    'showArrow' => true
])

<button
    type="submit"
    wire:loading.attr="disabled"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => 'w-full inline-flex justify-center items-center py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg shadow-indigo-500/25']) }}
>
    {{-- Loading spinner --}}
    <svg wire:loading wire:target="{{ $loadingTarget }}" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>

    @if ($disabled)
        {{-- Locked state --}}
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <span>{{ $lockedText }}</span>
    @else
        {{-- Normal state --}}
        <span wire:loading.remove wire:target="{{ $loadingTarget }}">{{ $text }}</span>
        <span wire:loading wire:target="{{ $loadingTarget }}">{{ $loadingText }}</span>

        @if ($showArrow)
            <svg wire:loading.remove wire:target="{{ $loadingTarget }}" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        @endif
    @endif
</button>
