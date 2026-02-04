<x-layouts.app :exception="$exception ?? null">
    @section('title', 'Accès non autorisé')

    <div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
        <div class="max-w-lg w-full text-center">
            <!-- Icon -->
            <div class="mb-8">
                <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-gradient-to-br from-red-100 to-red-200 shadow-lg">
                    <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-600 mb-4">
                403
            </h1>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                Accès non autorisé
            </h2>

            <!-- Message -->
            <p class="text-gray-600 mb-8 leading-relaxed max-w-md mx-auto">
                {{ $exception->getMessage() ?: 'Désolé, vous n\'avez pas les droits nécessaires pour accéder à cette page. Si vous pensez qu\'il s\'agit d\'une erreur, veuillez contacter votre administrateur.' }}
            </p>

            <!-- User Info -->
            @auth
                <div class="mb-8 inline-flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-full text-sm text-gray-600">
                    <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                        {{ auth()->user()->initials() }}
                    </div>
                    <span>{{ auth()->user()->name }}</span>
                    @if(auth()->user()->roles->isNotEmpty())
                        <span class="text-gray-400">•</span>
                        <span class="text-indigo-600 font-medium">{{ auth()->user()->roles->pluck('name')->join(', ') }}</span>
                    @endif
                </div>
            @endauth

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl text-white font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Tableau de bord
                </a>
            </div>

            <!-- Help text -->
            <p class="text-gray-400 text-sm mt-8">
                Besoin d'aide ? Contactez votre administrateur système.
            </p>
        </div>
    </div>
</x-layouts.app>
