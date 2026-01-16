<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['align' => 'right']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['align' => 'right']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{
        open: false,
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.positionDropdown());
            }
        },
        positionDropdown() {
            const button = this.$refs.button;
            const dropdown = this.$refs.dropdown;
            const rect = button.getBoundingClientRect();
            const dropdownHeight = dropdown.offsetHeight;
            const dropdownWidth = dropdown.offsetWidth;
            const viewportHeight = window.innerHeight;
            const viewportWidth = window.innerWidth;

            // Calcul vertical: afficher en bas ou en haut selon l'espace
            const spaceBelow = viewportHeight - rect.bottom;
            const spaceAbove = rect.top;

            if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                // Afficher au-dessus
                dropdown.style.top = (rect.top - dropdownHeight - 4) + 'px';
            } else {
                // Afficher en-dessous
                dropdown.style.top = (rect.bottom + 4) + 'px';
            }

            // Calcul horizontal: aligner à droite ou à gauche
            const alignRight = '<?php echo e($align); ?>' === 'right';
            if (alignRight) {
                const rightPos = viewportWidth - rect.right;
                dropdown.style.right = rightPos + 'px';
                dropdown.style.left = 'auto';
            } else {
                dropdown.style.left = rect.left + 'px';
                dropdown.style.right = 'auto';
            }
        }
    }"
    @resize.window="if(open) positionDropdown()"
    @scroll.window="if(open) positionDropdown()"
    class="relative inline-block text-left"
    <?php echo e($attributes); ?>

>
    <!-- Trigger Button -->
    <button
        x-ref="button"
        @click="toggle()"
        type="button"
        class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
        </svg>
    </button>

    <!-- Dropdown Menu (position fixed pour éviter les problèmes d'overflow) -->
    <div
        x-ref="dropdown"
        x-show="open"
        x-cloak
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed z-[9999] w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        style="transform-origin: top right;"
    >
        <div class="py-1 max-h-80 overflow-y-auto">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/components/actions-dropdown.blade.php ENDPATH**/ ?>