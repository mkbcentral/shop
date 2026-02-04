<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
     
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Super Admin</h1>
                <p class="text-sm text-gray-500 mt-1">Vue d'ensemble de la plateforme</p>
            </div>
            <livewire:admin.components.period-filter :periodFilter="$periodFilter" />
        </div>
    </div>

    <!-- Tab Navigation -->
    <livewire:admin.components.tab-navigation 
        :activeTab="$activeTab" 
        :tabs="[
            ['key' => 'overview', 'label' => 'Vue d\'ensemble'],
            ['key' => 'users', 'label' => 'Utilisateurs', 'badge' => $stats['total_users'] ?? 0],
            ['key' => 'organizations', 'label' => 'Organisations', 'badge' => $stats['total_organizations'] ?? 0],
            ['key' => 'subscriptions', 'label' => 'Abonnements']
        ]" 
    />

    <div class="space-y-6">
        <!-- Overview Tab -->
        @if ($activeTab === 'overview')
            <div class="space-y-6">
                <!-- Main Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Users Card -->
                    <livewire:admin.components.stats-card
                        title="Utilisateurs"
                        :value="number_format($stats['total_users'])"
                        subtitle="+{{ $stats['new_users_period'] }} cette période"
                        gradientFrom="indigo-500"
                        gradientTo="indigo-600"
                        :icon="'<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" 
                                d=\"M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\" />
                        </svg>'"
                        :footerStats="[
                            'Actifs' => $stats['active_users'],
                            'Inactifs' => $stats['inactive_users']
                        ]"
                    />

                    <!-- Organizations Card -->
                    <livewire:admin.components.stats-card
                        title="Organisations"
                        :value="number_format($stats['total_organizations'])"
                        subtitle="+{{ $stats['new_organizations_period'] }} cette période"
                        gradientFrom="purple-500"
                        gradientTo="purple-600"
                        :icon="'<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"
                                d=\"M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\" />
                        </svg>'"
                        :footerStats="[
                            'Payantes' => $stats['paid_organizations'],
                            'Essai' => $stats['trial_organizations']
                        ]"
                    />
                 
                    <!-- Revenue Card -->
                    <livewire:admin.components.stats-card
                        title="Revenus Abonnements"
                        :value="number_format($stats['subscription_revenue'], 0, ',', ' ')"
                        :subtitle="current_currency() . ' total'"
                        gradientFrom="green-500"
                        gradientTo="green-600"
                        :icon="'<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"
                                d=\"M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\" />
                        </svg>'"
                        footerLabel="Cette période"
                        :footerValue="number_format($stats['period_subscription_revenue'], 0, ',', ' ') . ' ' . current_currency()"
                    />

                    <!-- Stores Card -->
                    <livewire:admin.components.stats-card
                        title="Magasins"
                        :value="number_format($stats['total_stores'])"
                        subtitle="Dans toutes les organisations"
                        gradientFrom="amber-500"
                        gradientTo="amber-600"
                        :icon="'<svg class=\"w-8 h-8\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\"
                                d=\"M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z\" />
                        </svg>'"
                        footerLabel="Actifs"
                        :footerValue="$stats['active_stores']"
                    />
                </div>

                <!-- Charts & Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- User Growth Chart -->
                    <div class="lg:col-span-2">
                        <livewire:admin.components.user-growth-chart
                            :labels="$usersGrowth['labels']"
                            :values="$usersGrowth['values']"
                            title="Croissance des utilisateurs"
                        />
                    </div>

                    <!-- Recent Activity -->
                    <livewire:admin.components.recent-activity
                        :activities="$recentActivities"
                        title="Activité récente"
                    />
                </div>

                <!-- Quick Stats -->
                <livewire:admin.components.quick-stats-grid
                    :stats="[
                        ['value' => number_format($stats['total_products']), 'label' => 'Produits'],
                        ['value' => number_format($stats['total_sales_count']), 'label' => 'Ventes totales'],
                        ['value' => number_format($stats['total_sales_amount'], 0, ',', ' '), 'label' => 'CA Total (' . current_currency() . ')'],
                        ['value' => $stats['total_roles'], 'label' => 'Rôles']
                    ]"
                />
            </div>
        @endif

        <!-- Users Tab -->
        @if ($activeTab === 'users')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" wire:model.live.debounce.300ms="searchUsers"
                                placeholder="Rechercher un utilisateur..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <select wire:model.live="userStatusFilter"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="all">Tous les statuts</option>
                            <option value="active">Actifs</option>
                            <option value="inactive">Inactifs</option>
                        </select>
                        <a href="{{ route('users.index') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-center">
                            Gérer les utilisateurs
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rôles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Organisation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Inscrit le</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold">{{ $user->initials() }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($user->roles as $role)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                                    @if ($role->slug === 'super-admin') bg-red-100
                                                    @elseif($role->slug === 'admin') text-purple-800
                                                    @elseif($role->slug === 'manager')
                                                    @else @endif">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->defaultOrganization?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->is_active)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Actif</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if (!$user->hasRole('super-admin'))
                                            <button wire:click="toggleUserStatus({{ $user->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        Aucun utilisateur trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            </div>
        @endif

        <!-- Organizations Tab -->
        @if ($activeTab === 'organizations')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" wire:model.live.debounce.300ms="searchOrganizations"
                                placeholder="Rechercher une organisation..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <select wire:model.live="orgStatusFilter"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="all">Tous les statuts</option>
                            <option value="paid">Payantes</option>
                            <option value="trial">En essai</option>
                            <option value="expired">Expirées</option>
                        </select>
                        <a href="{{ route('organizations.index') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-center">
                            Gérer les organisations
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Organisation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Propriétaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Membres</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Magasins</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($organizations as $org)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $org->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $org->slug }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $org->owner?->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if ($org->subscription_plan?->value === 'premium') bg-amber-100
                                            @elseif($org->subscription_plan?->value === 'professional')
                                            @else text-gray-800 @endif">
                                            {{ ucfirst($org->subscription_plan?->value ?? 'starter') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $org->members_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $org->stores_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($org->payment_status?->value === 'completed')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Payée</span>
                                        @elseif($org->is_trial)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Essai</span>
                                        @elseif($org->subscription_ends_at && $org->subscription_ends_at < now())
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Expirée</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('organizations.show', $org) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">Voir</a>
                                        <button wire:click="toggleOrganizationStatus({{ $org->id }})"
                                            class="text-gray-600 hover:text-gray-900">
                                            {{ $org->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        Aucune organisation trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-200">
                    {{ $organizations->links() }}
                </div>
            </div>
        @endif

        <!-- Subscriptions Tab -->
        @if ($activeTab === 'subscriptions')
            <livewire:admin.components.subscriptions-overview :subscriptionStats="$subscriptionStats" />
        @endif
    </div>
</div>
