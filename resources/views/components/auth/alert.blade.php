@props([
    'type' => 'info',
    'title' => null,
    'message' => null,
    'dismissible' => false,
    'dismissAction' => null,
    'animate' => false
])

@php
    $types = [
        'success' => [
            'bg' => 'bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent',
            'border' => 'border-emerald-500/30',
            'iconBg' => 'bg-emerald-500/20',
            'iconColor' => 'text-emerald-400',
            'titleColor' => 'text-emerald-300',
            'textColor' => 'text-emerald-400/90',
            'dismissColor' => 'text-emerald-400/60 hover:text-emerald-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />',
        ],
        'error' => [
            'bg' => 'bg-gradient-to-r from-red-500/10 via-red-500/5 to-transparent',
            'border' => 'border-red-500/30',
            'iconBg' => 'bg-red-500/20',
            'iconColor' => 'text-red-400',
            'titleColor' => 'text-red-300',
            'textColor' => 'text-red-400/90',
            'dismissColor' => 'text-red-400/60 hover:text-red-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        ],
        'warning' => [
            'bg' => 'bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-transparent',
            'border' => 'border-amber-500/30',
            'iconBg' => 'bg-amber-500/20',
            'iconColor' => 'text-amber-400',
            'titleColor' => 'text-amber-300',
            'textColor' => 'text-amber-400/80',
            'dismissColor' => 'text-amber-400/60 hover:text-amber-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
        ],
        'info' => [
            'bg' => 'bg-gradient-to-r from-blue-500/10 via-blue-500/5 to-transparent',
            'border' => 'border-blue-500/30',
            'iconBg' => 'bg-blue-500/20',
            'iconColor' => 'text-blue-400',
            'titleColor' => 'text-blue-300',
            'textColor' => 'text-blue-400/90',
            'dismissColor' => 'text-blue-400/60 hover:text-blue-300',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        ],
    ];

    $config = $types[$type] ?? $types['info'];
    $animationClass = $animate ? 'animate-shake' : '';
@endphp

<div {{ $attributes->merge(['class' => "relative rounded-xl {$config['bg']} border {$config['border']} p-4 overflow-hidden {$animationClass}"]) }} role="alert">
    {{-- Radial gradient background --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_left,_var(--tw-gradient-stops))] from-{{ explode('-', $config['iconColor'])[1] }}-500/10 via-transparent to-transparent"></div>

    <div class="relative flex items-start gap-3">
        {{-- Icon --}}
        <div class="flex-shrink-0 w-10 h-10 rounded-full {{ $config['iconBg'] }} flex items-center justify-center {{ $type === 'warning' ? 'animate-pulse' : '' }}">
            <svg class="w-5 h-5 {{ $config['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $config['icon'] !!}
            </svg>
        </div>

        {{-- Content --}}
        <div class="flex-1 pt-0.5">
            @if($title)
                <p class="text-sm font-medium {{ $config['titleColor'] }}">{{ $title }}</p>
            @endif
            @if($message)
                <p class="text-sm {{ $config['textColor'] }} {{ $title ? 'mt-0.5' : '' }}">{{ $message }}</p>
            @endif
            {{ $slot }}
        </div>

        {{-- Dismiss button --}}
        @if($dismissible)
            <button type="button"
                @if($dismissAction)
                    wire:click="{{ $dismissAction }}"
                @else
                    x-data x-on:click="$el.closest('[role=alert]').remove()"
                @endif
                class="flex-shrink-0 {{ $config['dismissColor'] }} transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>
</div>
