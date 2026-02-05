@props([
    'feature' => null,
    'features' => [],
    'minPlan' => 'Starter',
    'message' => null,
    'inline' => false,
    'showContent' => false,
])

@php
    $featuresToCheck = $feature ? [$feature] : (array) $features;
    $hasAccess = false;
    $currentOrg = null;

    if (auth()->check()) {
        $user = auth()->user();

        // Super-admin a accès à tout
        if ($user->hasRole('super-admin')) {
            $hasAccess = true;
        } else {
            $currentOrg = app()->bound('current_organization')
                ? app('current_organization')
                : ($user->currentOrganization ?? $user->defaultOrganization);

            if ($currentOrg && !empty($featuresToCheck)) {
                $planLimitService = app(\App\Services\PlanLimitService::class);
                foreach ($featuresToCheck as $f) {
                    if ($planLimitService->hasFeature($f, $currentOrg)) {
                        $hasAccess = true;
                        break;
                    }
                }
            }
        }
    }

    // Labels des fonctionnalités
    $featureLabels = [
        'basic_pos' => 'Point de vente',
        'basic_inventory' => 'Gestion de stock de base',
        'basic_reports' => 'Rapports de base',
        'advanced_reports' => 'Rapports avancés',
        'multi_store' => 'Multi-magasins',
        'export_excel' => 'Export Excel',
        'export_pdf' => 'Export PDF',
        'api_access' => 'Accès API',
        'custom_reports' => 'Rapports personnalisés',
        'integrations' => 'Intégrations tierces',
        'unlimited' => 'Ressources illimitées',
        'dedicated_support' => 'Support dédié',
        'custom_development' => 'Développement sur mesure',
        'sla' => 'SLA garanti',
    ];

    $featureLabel = $feature ? ($featureLabels[$feature] ?? $feature) : '';
    $defaultMessage = $message ?? "Cette fonctionnalité nécessite un plan {$minPlan} ou supérieur.";
@endphp

@if($hasAccess)
    {{ $slot }}
@else
    @if($inline)
        {{-- Version inline pour boutons/liens --}}
        <div class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 font-medium rounded-lg border border-gray-200 cursor-not-allowed"
             title="{{ $defaultMessage }}">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            @if($featureLabel)
                <span>{{ $featureLabel }}</span>
            @else
                {{ $slot }}
            @endif
            <span class="ml-2 text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">{{ $minPlan }}+</span>
        </div>
    @elseif($showContent)
        {{-- Version avec contenu grisé et overlay --}}
        <div {{ $attributes->merge(['class' => 'relative']) }}>
            {{-- Contenu désactivé --}}
            <div class="opacity-50 pointer-events-none select-none">
                {{ $slot }}
            </div>

            {{-- Overlay avec cadenas --}}
            <div class="absolute inset-0 flex items-center justify-center bg-gray-100/80 rounded-lg backdrop-blur-sm">
                <div class="text-center p-4">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-amber-100 rounded-full mb-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">{{ $defaultMessage }}</p>
                    @if($currentOrg)
                        <a href="{{ route('organizations.subscription', $currentOrg) }}"
                           class="inline-flex items-center mt-3 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Mettre à niveau
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @else
        {{-- Version bloc complet (remplacement) --}}
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-700 mb-2">
                @if($featureLabel)
                    {{ $featureLabel }}
                @else
                    Fonctionnalité Premium
                @endif
            </h3>
            <p class="text-gray-500 mb-4 max-w-md mx-auto">{{ $defaultMessage }}</p>

            @if($currentOrg)
                <a href="{{ route('organizations.subscription', $currentOrg) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Mettre à niveau
                </a>
            @endif
        </div>
    @endif
@endif
