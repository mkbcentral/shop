<div class="min-h-screen flex">
    <!-- Left Column - Form -->
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Vérification de l'email</h2>
                <p class="text-gray-600 mt-2">
                    Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons un autre avec plaisir.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <x-form.alert type="success" message="Un nouveau lien de vérification a été envoyé à votre adresse e-mail." class="mb-6" />
            @endif

            <div class="flex items-center justify-between gap-4">
                <x-form.button wire:click="resendVerification">
                    Renvoyer l'email de vérification
                </x-form.button>

                <button wire:click="logout" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Se déconnecter
                </button>
            </div>
        </div>
    </div>

    <!-- Right Column - Gradient Info -->
    <div class="hidden lg:flex lg:flex-1 bg-gradient-to-br from-indigo-600 to-purple-700 p-12 items-center justify-center text-white">
        <div class="max-w-md">
            <h3 class="text-4xl font-bold mb-6">Sécurisez votre compte</h3>
            <p class="text-xl text-indigo-100 mb-8">
                La vérification de votre adresse e-mail nous aide à garantir la sécurité de votre compte et à vous envoyer des notifications importantes.
            </p>
            <ul class="space-y-4">
                <li class="flex items-start">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-indigo-100">Protection de votre compte</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-indigo-100">Récupération de mot de passe</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-indigo-100">Notifications importantes</span>
                </li>
            </ul>
        </div>
    </div>
</div>
