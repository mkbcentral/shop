@props([
    'showDeveloper' => true
])

<div {{ $attributes->merge(['class' => 'flex items-center justify-center flex-wrap gap-x-4 gap-y-2 pt-2']) }}>
    {{-- SSL Badge --}}
    <div class="flex items-center gap-1.5 text-xs text-slate-500">
        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span>SSL sécurisé</span>
    </div>

    {{-- Data Protection Badge --}}
    <div class="flex items-center gap-1.5 text-xs text-slate-500">
        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
        </svg>
        <span>Données protégées</span>
    </div>

    {{-- Developer Badge --}}
    @if ($showDeveloper && config('app.developer_name'))
        <div class="flex items-center gap-1.5 text-xs text-slate-500">
            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            <span>Développé par <a href="{{ config('app.developer_url', '#') }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 transition">{{ config('app.developer_name') }}</a></span>
        </div>
    @endif
</div>
