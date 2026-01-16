{{-- Placeholder de préchargement lors de la navigation --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-indigo-50/30 flex items-center justify-center p-4">
    {{-- Style pour l'animation de la barre de chargement --}}
    <style>
        @keyframes loading-bar {
            0% {
                width: 0%;
                margin-left: 0%;
            }
            50% {
                width: 60%;
                margin-left: 20%;
            }
            100% {
                width: 0%;
                margin-left: 100%;
            }
        }

        .animate-loading-bar {
            animation: loading-bar 1.5s ease-in-out infinite;
        }
    </style>

    <div class="text-center">
        {{-- Logo animé --}}
        <div class="relative mb-8">
            {{-- Cercles d'animation en arrière-plan --}}
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 rounded-full border-4 border-indigo-100 animate-ping opacity-20"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-20 h-20 rounded-full border-4 border-indigo-200 animate-pulse"></div>
            </div>

            {{-- Icône centrale --}}
            <div class="relative w-20 h-20 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl shadow-indigo-500/30 flex items-center justify-center animate-pulse">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>

        {{-- Spinner moderne --}}
        <div class="flex justify-center mb-6">
            <div class="relative">
                {{-- Cercle extérieur --}}
                <div class="w-12 h-12 rounded-full border-4 border-gray-200"></div>
                {{-- Cercle animé --}}
                <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-transparent border-t-indigo-600 border-r-purple-500 animate-spin"></div>
            </div>
        </div>

        {{-- Texte de chargement --}}
        <div class="space-y-2">
            <h3 class="text-lg font-bold text-gray-800">
                Chargement en cours
                <span class="inline-flex ml-1">
                    <span class="animate-bounce" style="animation-delay: 0ms">.</span>
                    <span class="animate-bounce" style="animation-delay: 150ms">.</span>
                    <span class="animate-bounce" style="animation-delay: 300ms">.</span>
                </span>
            </h3>
            <p class="text-sm text-gray-500">Veuillez patienter un instant</p>
        </div>

        {{-- Barre de progression animée --}}
        <div class="mt-8 max-w-xs mx-auto">
            <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500 rounded-full animate-loading-bar"></div>
            </div>
        </div>

        {{-- Skeleton preview des éléments à charger --}}
        <div class="mt-10 max-w-md mx-auto opacity-40">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-3 bg-gray-200 rounded-full w-3/4 animate-pulse"></div>
                        <div class="h-2 bg-gray-100 rounded-full w-1/2 animate-pulse"></div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="h-16 bg-gray-100 rounded-lg animate-pulse"></div>
                    <div class="h-16 bg-gray-100 rounded-lg animate-pulse" style="animation-delay: 100ms"></div>
                    <div class="h-16 bg-gray-100 rounded-lg animate-pulse" style="animation-delay: 200ms"></div>
                </div>
            </div>
        </div>
    </div>
</div>
