@php
    use App\Services\MenuService;

    $menuService = app(MenuService::class);
    $user = auth()->user();
    $menusBySection = $menuService->getAccessibleMenusForUser($user);
@endphp

<x-sidebar>
    <div class="space-y-1">
        {{-- Menus sans section (Dashboard, etc.) --}}
        @if($menusBySection->has('no_section'))
            @foreach($menusBySection->get('no_section') as $menu)
                @if($menu->children->isEmpty())
                    {{-- Menu simple sans enfants --}}
                    <x-sidebar-item
                        href="{{ $menu->route ? route($menu->route) : ($menu->url ?? '#') }}"
                        :active="$menu->route ? request()->routeIs($menu->route) : false"
                        :icon="$menu->icon"
                        :badge="$menu->badge_type === 'text' ? ($menu->badge_value ?? null) : null"
                        :badgeColor="$menu->badge_color">
                        {{ $menu->name }}
                    </x-sidebar-item>
                @else
                    {{-- Menu avec enfants (dropdown) --}}
                    <x-sidebar-dropdown
                        title="{{ $menu->name }}"
                        :badge="$menu->badge_type === 'count' ? (${$menu->code . '_count'} ?? 0) : null"
                        :badgeColor="$menu->badge_color"
                        activePattern="{{ $menu->code }}/*"
                        :icon="$menu->icon">

                        @foreach($menu->children as $child)
                            <x-sidebar-item
                                href="{{ $child->route ? route($child->route) : ($child->url ?? '#') }}"
                                :active="$child->route ? request()->routeIs($child->route) : false"
                                :isDropdownItem="true"
                                :badge="$child->badge_type === 'count' ? (${$child->code . '_count'} ?? null) : null"
                                :badgeColor="$child->badge_color"
                                :animate="$child->badge_type === 'count' && isset(${$child->code . '_count'}) && ${$child->code . '_count'} > 0">
                                {{ $child->name }}
                            </x-sidebar-item>
                        @endforeach
                    </x-sidebar-dropdown>
                @endif
            @endforeach
        @endif

        {{-- Menus groupés par section --}}
        @foreach($menusBySection as $sectionName => $sectionMenus)
            @if($sectionName !== 'no_section' && $sectionMenus->isNotEmpty())
                <x-sidebar-section title="{{ $sectionName }}">
                    @foreach($sectionMenus as $menu)
                        @if($menu->children->isEmpty())
                            {{-- Menu simple sans enfants --}}
                            <x-sidebar-item
                                href="{{ $menu->route ? route($menu->route) : ($menu->url ?? '#') }}"
                                :active="$menu->route ? request()->routeIs($menu->route . '*') : false"
                                :icon="$menu->icon"
                                :badge="$menu->badge_type === 'text' ? ($menu->badge_value ?? 'POS') : null"
                                :badgeColor="$menu->badge_color"
                                :animate="$menu->badge_type === 'text'">
                                {{ $menu->name }}
                            </x-sidebar-item>
                        @else
                            {{-- Menu avec enfants (dropdown) --}}
                            <x-sidebar-dropdown
                                title="{{ $menu->name }}"
                                :badge="$menu->badge_type === 'count' ? (${$menu->code . '_count'} ?? (${'total_' . $menu->code} ?? 0)) : null"
                                :badgeColor="$menu->badge_color"
                                activePattern="{{ $menu->code }}/*"
                                :icon="$menu->icon">

                                @foreach($menu->children as $child)
                                    <x-sidebar-item
                                        href="{{ $child->route ? route($child->route) : ($child->url ?? '#') }}"
                                        :active="$child->route ? request()->routeIs($child->route) : false"
                                        :isDropdownItem="true"
                                        :badge="$child->badge_type === 'count' ? (${$child->code . '_count'} ?? ($low_stock_alerts ?? null)) : null"
                                        :badgeColor="$child->badge_color"
                                        :animate="$child->badge_color === 'red' && isset($low_stock_alerts) && $low_stock_alerts > 0">
                                        {{ $child->name }}
                                    </x-sidebar-item>
                                @endforeach
                            </x-sidebar-dropdown>
                        @endif
                    @endforeach
                </x-sidebar-section>
            @endif
        @endforeach
    </div>
</x-sidebar>
