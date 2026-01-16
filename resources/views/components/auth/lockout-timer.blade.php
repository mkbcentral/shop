@props([
    'seconds' => 0,
    'title' => 'Compte temporairement bloqué',
    'subtitle' => 'Trop de tentatives échouées. Veuillez patienter.'
])

<div {{ $attributes->merge(['class' => 'relative rounded-xl bg-gradient-to-r from-red-600/20 via-red-500/10 to-transparent border border-red-500/40 p-4 overflow-hidden']) }}
    role="alert"
    x-data="{
        seconds: {{ $seconds }},
        initialSeconds: {{ $seconds }},
        interval: null,
        init() {
            this.interval = setInterval(() => {
                if (this.seconds > 0) {
                    this.seconds--;
                } else {
                    clearInterval(this.interval);
                    $wire.$refresh();
                }
            }, 1000);
        },
        get minutes() {
            return Math.floor(this.seconds / 60);
        },
        get remainingSeconds() {
            return this.seconds % 60;
        },
        get formattedTime() {
            return this.minutes + ':' + String(this.remainingSeconds).padStart(2, '0');
        },
        get progress() {
            return ((this.initialSeconds - this.seconds) / this.initialSeconds) * 100;
        }
    }"
    x-init="init()">

    {{-- Animated background --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_left,_var(--tw-gradient-stops))] from-red-500/15 via-transparent to-transparent"></div>

    {{-- Progress bar at top --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-red-900/30">
        <div class="h-full bg-gradient-to-r from-red-500 to-red-400 transition-all duration-1000 ease-linear" :style="'width: ' + progress + '%'"></div>
    </div>

    <div class="relative flex items-center gap-4 pt-1">
        {{-- Lock icon --}}
        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-500/20 flex items-center justify-center border border-red-500/30">
            <svg class="w-6 h-6 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        {{-- Text content --}}
        <div class="flex-1">
            <p class="text-sm font-semibold text-red-300">{{ $title }}</p>
            <p class="text-xs text-red-400/80 mt-1">{{ $subtitle }}</p>
        </div>

        {{-- Timer display --}}
        <div class="flex-shrink-0 text-center">
            <div class="text-2xl font-mono font-bold text-red-300" x-text="formattedTime"></div>
            <div class="text-xs text-red-400/60">restant</div>
        </div>
    </div>
</div>
