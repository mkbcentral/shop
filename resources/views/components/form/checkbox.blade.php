@props([
    'name' => '',
    'id' => '',
    'checked' => false,
    'disabled' => false,
    'size' => 'md', // 'sm', 'md', 'lg'
])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];

    $checkboxSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<label class="relative inline-flex items-center group cursor-pointer {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $id ?: $name }}"
        @if($checked) checked @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge([
            'class' => "peer sr-only"
        ]) }}
    >
    <div class="{{ $checkboxSize }} rounded-md border-2 border-gray-300 bg-white transition-all duration-200 ease-in-out
                peer-checked:bg-indigo-600 peer-checked:border-indigo-600
                peer-focus:ring-4 peer-focus:ring-indigo-100 peer-focus:ring-offset-0
                {{ !$disabled ? 'peer-hover:border-indigo-400 group-hover:border-indigo-400 peer-active:scale-95' : '' }}
                flex items-center justify-center shadow-sm hover:shadow-md">
        <!-- Checkmark Icon -->
        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-all duration-200 transform peer-checked:scale-100 scale-0"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
</label>

<style>
    /* Smooth animation for checkmark */
    input[type="checkbox"]:checked ~ div svg {
        animation: checkmarkAppear 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes checkmarkAppear {
        0% {
            transform: scale(0) rotate(-45deg);
            opacity: 0;
        }
        50% {
            transform: scale(1.2) rotate(5deg);
        }
        100% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }

    /* Background color transition */
    input[type="checkbox"]:checked ~ div {
        animation: checkboxFill 0.2s ease-in-out;
    }

    @keyframes checkboxFill {
        0% {
            background-color: transparent;
        }
        100% {
            background-color: rgb(79 70 229);
        }
    }
</style>
