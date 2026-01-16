@props([
    'title' => '',
    'subtitle' => null,
    'icon' => null,
    'iconBg' => 'from-indigo-500 to-purple-600',
    'closeable' => true,
])

<div {{ $attributes->merge(['class' => 'flex-shrink-0 flex items-center justify-between p-5 sm:p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl']) }}>
    <div class="flex items-center space-x-3">
        @if($icon)
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br {{ $iconBg }} rounded-xl flex items-center justify-center shadow-lg">
                @if(is_string($icon) && str_starts_with($icon, '<'))
                    {!! $icon !!}
                @else
                    {{ $icon }}
                @endif
            </div>
        @endif
        <div>
            <h3 id="modal-title" class="text-lg sm:text-xl font-bold text-gray-900">
                {{ $title }}
            </h3>
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    @if($closeable)
        <button
            @click="close()"
            type="button"
            class="text-gray-400 hover:text-gray-600 transition-all duration-200 hover:scale-110 hover:bg-gray-100 rounded-lg p-1"
        >
            <span class="sr-only">Fermer</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
</div>
