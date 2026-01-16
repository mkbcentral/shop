@props(['align' => 'left', 'sortable' => false, 'sortKey' => null])

@php
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<th {{ $attributes->merge(['class' => "px-6 py-3 {$alignClass} text-xs font-medium text-gray-500 uppercase tracking-wider"]) }}>
    @if($sortable && $sortKey)
        <button type="button" wire:click="sortBy('{{ $sortKey }}')" class="group inline-flex items-center space-x-1 hover:text-gray-700 transition">
            <span>{{ $slot }}</span>
            @if(isset($this) && property_exists($this, 'sortField') && $this->sortField === $sortKey)
                @if(isset($this) && property_exists($this, 'sortDirection') && $this->sortDirection === 'asc')
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                @else
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                @endif
            @else
                <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                </svg>
            @endif
        </button>
    @else
        {{ $slot }}
    @endif
</th>
