
<div class="bg-gradient-to-br from-{{ $gradientFrom }} to-{{ $gradientTo }} rounded-xl p-6 text-white shadow-lg">
     
    <div class="flex items-center justify-between">
        <div>
            <p class="text-{{ $gradientFrom }}-100 text-sm font-medium">{{ $title }}</p>
            <p class="text-3xl font-bold mt-1">{{ $value }}</p>
            <p class="text-{{ $gradientFrom }}-100 text-xs mt-2">{{ $subtitle }}</p>
        </div>
        <div class="bg-white/20 rounded-full p-3">
            {!! $icon !!}
        </div>
    </div>

    @if($footerLabel && $footerValue)
        <div class="mt-4 pt-4 border-t border-white/20">
            <span class="text-{{ $gradientFrom }}-100 text-sm">{{ $footerLabel }}: {{ $footerValue }}</span>
        </div>
    @endif

    @if($footerStats && count($footerStats) > 0)
        <div class="mt-4 pt-4 border-t border-white/20">
            <div class="flex justify-between text-sm">
                @foreach($footerStats as $label => $value)
                    <span class="text-{{ $gradientFrom }}-100">{{ $label }}: {{ $value }}</span>
                @endforeach
            </div>
        </div>
    @endif
</div>
