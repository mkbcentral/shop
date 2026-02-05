@props([
    'feature' => null,
    'minPlan' => 'Starter',
    'variant' => 'secondary',
    'size' => 'md',
    'icon' => null,
])

@hasfeature($feature)
    <x-form.button {{ $attributes }} :variant="$variant" :size="$size" :icon="$icon">
        {{ $slot }}
    </x-form.button>
@else
    <button 
        type="button" 
        disabled 
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed opacity-60']) }}
        title="Plan {{ $minPlan }} requis"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        {{ $slot }}
        <span class="ml-2 text-xs text-amber-500 font-medium">({{ $minPlan }}+)</span>
    </button>
@endhasfeature
