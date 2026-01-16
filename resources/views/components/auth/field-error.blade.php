@props([
    'for' => null,
    'message' => null
])

@if($message || ($for && $errors->has($for)))
    <div {{ $attributes->merge(['class' => 'mt-2 flex items-start gap-2 p-2 rounded-lg bg-red-500/10 border border-red-500/20']) }}>
        <svg class="w-4 h-4 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span class="text-sm text-red-400">{{ $message ?? $errors->first($for) }}</span>
    </div>
@endif
