<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            @foreach($tabs as $tab)
                <button wire:click="setActiveTab('{{ $tab['key'] }}')" 
                    class="relative px-6 py-4 text-sm font-medium border-b-2 transition-colors
                        @if($activeTab === $tab['key'])
                            border-indigo-600 text-indigo-600
                        @else
                            border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                        @endif">
                    <!-- Loading indicator on specific button -->
                    <div wire:loading wire:target="setActiveTab('{{ $tab['key'] }}')" 
                         class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-t-lg">
                        <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if(isset($tab['icon']))
                            {!! $tab['icon'] !!}
                        @endif
                        <span>{{ $tab['label'] }}</span>
                        @if(isset($tab['badge']))
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full 
                                @if($activeTab === $tab['key']) bg-indigo-100 text-indigo-600
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $tab['badge'] }}
                            </span>
                        @endif
                    </div>
                </button>
            @endforeach
        </nav>
    </div>
</div>
