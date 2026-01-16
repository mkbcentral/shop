<div x-data="{ showModal: false, showDeleteModal: false, clientToDelete: null, clientName: '', isEditing: false }"
     @open-client-modal.window="showModal = true"
     @open-edit-modal.window="isEditing = true; showModal = true"
     @close-client-modal.window="showModal = false; isEditing = false">
    <!-- Toast Notifications -->
    <?php if (isset($component)) { $__componentOriginal7cfab914afdd05940201ca0b2cbc009b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $attributes = $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $component = $__componentOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>

     <?php $__env->slot('header', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Clients']
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Accueil', 'url' => route('dashboard')],
            ['label' => 'Clients']
        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>

    <div class="flex items-center justify-between mt-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Clients</h1>
            <p class="text-gray-500 mt-1">Gérez vos clients et leurs informations</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="isEditing = false; showModal = true; $wire.openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau Client
            </button>
        </div>
    </div>

    <div class="space-y-6 mt-6">
        <!-- Clients Table -->
        <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('header', null, []); ?> 
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Liste des Clients (<?php echo e($clients->total()); ?>)</h2>
                    <div class="w-72">
                        <?php if (isset($component)) { $__componentOriginal894294112bf23c4166443c90d4833959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal894294112bf23c4166443c90d4833959 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.search-input','data' => ['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model.live.debounce.300ms' => 'search','wireModel' => 'search','placeholder' => 'Rechercher...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal894294112bf23c4166443c90d4833959)): ?>
<?php $attributes = $__attributesOriginal894294112bf23c4166443c90d4833959; ?>
<?php unset($__attributesOriginal894294112bf23c4166443c90d4833959); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal894294112bf23c4166443c90d4833959)): ?>
<?php $component = $__componentOriginal894294112bf23c4166443c90d4833959; ?>
<?php unset($__componentOriginal894294112bf23c4166443c90d4833959); ?>
<?php endif; ?>
                    </div>
                </div>
             <?php $__env->endSlot(); ?>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('name')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center space-x-1">
                                    <span>Nom</span>
                                    <!--[if BLOCK]><![endif]--><?php if($sortField === 'name'): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M<?php echo e($sortDirection === 'asc' ? '5 15l7-7 7 7' : '19 9l-7 7-7-7'); ?>" />
                                        </svg>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Téléphone
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Adresse
                            </th>
                            <th wire:click="sortBy('created_at')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center space-x-1">
                                    <span>Date de création</span>
                                    <!--[if BLOCK]><![endif]--><?php if($sortField === 'created_at'): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M<?php echo e($sortDirection === 'asc' ? '5 15l7-7 7 7' : '19 9l-7 7-7-7'); ?>" />
                                        </svg>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($client->name); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($client->phone ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($client->email ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?php echo e($client->address ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?php echo e($client->created_at->format('d/m/Y')); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button @click="$wire.openEditModal(<?php echo e($client->id); ?>)"
                                            class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="showDeleteModal = true; clientToDelete = <?php echo e($client->id); ?>; clientName = '<?php echo e($client->name); ?>'"
                                            class="text-red-600 hover:text-red-900 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-500">Aucun client trouvé</p>
                                </td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($clients->hasPages()): ?>
                <div class="mt-4">
                    <?php echo e($clients->links()); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
    </div>

    <!-- Modal - Géré uniquement par Alpine.js -->
    <div x-show="showModal"
         x-cloak
         x-on:keydown.escape.window="showModal = false"
         x-init="$watch('showModal', value => { document.body.style.overflow = value ? 'hidden' : '' })"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop -->
        <div @click="showModal = false"
             x-show="showModal"
             x-transition.opacity.duration.150ms
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm">
        </div>

        <!-- Modal Container -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div x-show="showModal"
                 @click.stop
                 x-transition:enter="ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl transform w-full sm:max-w-lg flex flex-col pointer-events-auto"
                 style="max-height: 90vh;">

                <!-- Modal Header -->
                <div class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900" x-text="isEditing ? 'Modifier le Client' : 'Nouveau Client'"></h3>
                    </div>
                    <button @click="showModal = false" type="button"
                        class="rounded-lg p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <!-- Modal Body -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-5">
                            <!-- Name Field -->
                            <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Nom du client','for' => 'name','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Nom du client','for' => 'name','required' => true]); ?>
                                <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model' => 'name','id' => 'name','type' => 'text','placeholder' => 'Ex: Jean Dupont, Société ABC...','icon' => 'user']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'name','id' => 'name','type' => 'text','placeholder' => 'Ex: Jean Dupont, Société ABC...','icon' => 'user']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'name']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>

                            <!-- Phone & Email Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Phone Field -->
                                <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Téléphone','for' => 'phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Téléphone','for' => 'phone']); ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model' => 'phone','id' => 'phone','type' => 'text','placeholder' => 'Numéro de téléphone','icon' => 'phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'phone','id' => 'phone','type' => 'text','placeholder' => 'Numéro de téléphone','icon' => 'phone']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'phone']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>

                                <!-- Email Field -->
                                <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Email','for' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','for' => 'email']); ?>
                                    <?php if (isset($component)) { $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input','data' => ['wire:model' => 'email','id' => 'email','type' => 'email','placeholder' => 'Adresse email','icon' => 'mail']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'email','id' => 'email','type' => 'email','placeholder' => 'Adresse email','icon' => 'mail']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $attributes = $__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__attributesOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b)): ?>
<?php $component = $__componentOriginal5c2a97ab476b69c1189ee85d1a95204b; ?>
<?php unset($__componentOriginal5c2a97ab476b69c1189ee85d1a95204b); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
                            </div>

                            <!-- Address Field -->
                            <?php if (isset($component)) { $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.form-group','data' => ['label' => 'Adresse','for' => 'address']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Adresse','for' => 'address']); ?>
                                <textarea id="address" wire:model="address" rows="2"
                                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                    placeholder="Adresse complète du client..."></textarea>
                                <?php if (isset($component)) { $__componentOriginal8d499d75702cee5e9aae94bf7f660f12 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.input-error','data' => ['for' => 'address']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form.input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'address']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $attributes = $__attributesOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__attributesOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12)): ?>
<?php $component = $__componentOriginal8d499d75702cee5e9aae94bf7f660f12; ?>
<?php unset($__componentOriginal8d499d75702cee5e9aae94bf7f660f12); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $attributes = $__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__attributesOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8)): ?>
<?php $component = $__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8; ?>
<?php unset($__componentOriginal0999d118bcdb5e6f1f07a745e9965ff8); ?>
<?php endif; ?>

                            <!-- Info Box -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Les clients vous permettent de suivre vos ventes et de gérer vos relations commerciales.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex-shrink-0 flex justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50/50 rounded-b-2xl">
                        <button type="button" @click="showModal = false"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50"
                            wire:loading.attr="disabled">
                            <svg wire:loading.remove wire:target="save" class="w-5 h-5 mr-2 inline-block" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="save" class="animate-spin w-5 h-5 mr-2 inline-block"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="save" x-text="isEditing ? 'Mettre à jour' : 'Enregistrer'"></span>
                            <span wire:loading wire:target="save">Enregistrement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <?php if (isset($component)) { $__componentOriginal8b7b112f0fae85419ee5abf8337434ab = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8b7b112f0fae85419ee5abf8337434ab = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-confirmation-modal','data' => ['show' => 'showDeleteModal','itemName' => 'clientName','itemType' => 'le client','onConfirm' => '$wire.delete(clientToDelete); showDeleteModal = false; clientToDelete = null; clientName = \'\'','onCancel' => 'showDeleteModal = false; clientToDelete = null; clientName = \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-confirmation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show' => 'showDeleteModal','itemName' => 'clientName','itemType' => 'le client','onConfirm' => '$wire.delete(clientToDelete); showDeleteModal = false; clientToDelete = null; clientName = \'\'','onCancel' => 'showDeleteModal = false; clientToDelete = null; clientName = \'\'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8b7b112f0fae85419ee5abf8337434ab)): ?>
<?php $attributes = $__attributesOriginal8b7b112f0fae85419ee5abf8337434ab; ?>
<?php unset($__attributesOriginal8b7b112f0fae85419ee5abf8337434ab); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8b7b112f0fae85419ee5abf8337434ab)): ?>
<?php $component = $__componentOriginal8b7b112f0fae85419ee5abf8337434ab; ?>
<?php unset($__componentOriginal8b7b112f0fae85419ee5abf8337434ab); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/client/client-index.blade.php ENDPATH**/ ?>