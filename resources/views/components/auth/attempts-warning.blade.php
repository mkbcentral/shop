@props([
    'remaining' => 5,
    'max' => 5
])

@if ($remaining < $max && $remaining > 0)
<div {{ $attributes->merge(['class' => 'relative rounded-xl bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-transparent border border-amber-500/30 p-4 overflow-hidden']) }} role="alert">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_left,_var(--tw-gradient-stops))] from-amber-500/10 via-transparent to-transparent"></div>
    <div class="relative flex items-center gap-3">
        {{-- Warning icon --}}
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center animate-pulse">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        {{-- Text --}}
        <div class="flex-1">
            <p class="text-sm font-medium text-amber-300">Attention</p>
            <p class="text-sm text-amber-400/80 mt-0.5">
                <span class="inline-flex items-center gap-1.5">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-500/30 text-xs font-bold text-amber-300">{{ $remaining }}</span>
                    tentative{{ $remaining > 1 ? 's' : '' }} restante{{ $remaining > 1 ? 's' : '' }} avant blocage
                </span>
            </p>
        </div>

        {{-- Progress bar --}}
        <div class="flex-shrink-0 w-16 h-1.5 bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-amber-500 to-red-500 rounded-full transition-all duration-300" style="width: {{ (($max - $remaining) / $max) * 100 }}%"></div>
        </div>
    </div>
</div>
@endif
