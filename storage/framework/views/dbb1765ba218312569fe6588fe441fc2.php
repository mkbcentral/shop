<div>
    <!--[if BLOCK]><![endif]--><?php if(count($productAttributes) > 0): ?>
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center space-x-2 mb-4">
                <div class="h-8 w-1 bg-gradient-to-b from-purple-500 to-pink-600 rounded-full"></div>
                <h3 class="text-lg font-semibold text-gray-800">Attributs du produit</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $productAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label for="attr_<?php echo e($attribute['id']); ?>" class="block text-sm font-semibold text-gray-700 mb-2">
                            <?php echo e($attribute['name']); ?>

                            <!--[if BLOCK]><![endif]--><?php if($attribute['is_required']): ?>
                                <span class="text-red-500">*</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($attribute['is_variant_attribute']): ?>
                                <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">Variant</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </label>

                        <!--[if BLOCK]><![endif]--><?php if($attribute['type'] === 'text'): ?>
                            <input
                                type="text"
                                id="attr_<?php echo e($attribute['id']); ?>"
                                wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                placeholder="<?php echo e($attribute['default_value'] ?? ''); ?>"
                                <?php if($attribute['is_required']): ?> required <?php endif; ?>
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">

                        <?php elseif($attribute['type'] === 'number'): ?>
                            <div class="relative">
                                <input
                                    type="number"
                                    id="attr_<?php echo e($attribute['id']); ?>"
                                    wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                    placeholder="<?php echo e($attribute['default_value'] ?? '0'); ?>"
                                    step="0.01"
                                    <?php if($attribute['is_required']): ?> required <?php endif; ?>
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                                <!--[if BLOCK]><![endif]--><?php if($attribute['unit']): ?>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"><?php echo e($attribute['unit']); ?></span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                        <?php elseif($attribute['type'] === 'select'): ?>
                            <!--[if BLOCK]><![endif]--><?php if($attribute['is_variant_attribute']): ?>
                                <!-- Multi-select avec checkboxes pour les variantes -->
                                <div class="space-y-2">
                                    <div class="text-xs text-gray-500 mb-2">Sélectionnez une ou plusieurs options pour générer les variantes</div>
                                    <div class="max-h-40 overflow-y-auto space-y-2 p-2 bg-gray-50 rounded-lg border border-gray-200">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $attribute['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center cursor-pointer hover:bg-white p-2 rounded transition-colors duration-150">
                                                <input
                                                    type="checkbox"
                                                    wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                                    value="<?php echo e($option); ?>"
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                                                <span class="ml-2 text-sm text-gray-700"><?php echo e($option); ?></span>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Select simple pour les attributs non-variantes -->
                                <select
                                    id="attr_<?php echo e($attribute['id']); ?>"
                                    wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                    <?php if($attribute['is_required']): ?> required <?php endif; ?>
                                    class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                                    <option value="">Sélectionner...</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $attribute['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($option); ?>"><?php echo e($option); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <?php elseif($attribute['type'] === 'boolean'): ?>
                            <label class="flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    id="attr_<?php echo e($attribute['id']); ?>"
                                    wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                    value="1"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                                <span class="ml-2 text-sm text-gray-700"><?php echo e($attribute['default_value'] ?? 'Oui'); ?></span>
                            </label>

                        <?php elseif($attribute['type'] === 'date'): ?>
                            <input
                                type="date"
                                id="attr_<?php echo e($attribute['id']); ?>"
                                wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                <?php if($attribute['is_required']): ?> required <?php endif; ?>
                                class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">

                        <?php elseif($attribute['type'] === 'color'): ?>
                            <div class="flex gap-2">
                                <input
                                    type="color"
                                    id="attr_<?php echo e($attribute['id']); ?>_picker"
                                    wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                    class="h-10 w-16 rounded-lg border-2 border-gray-300 cursor-pointer">
                                <input
                                    type="text"
                                    id="attr_<?php echo e($attribute['id']); ?>"
                                    wire:model="attributeValues.<?php echo e($attribute['id']); ?>"
                                    placeholder="#000000"
                                    <?php if($attribute['is_required']): ?> required <?php endif; ?>
                                    class="flex-1 px-4 py-2.5 rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200">
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if($attribute['unit'] && $attribute['type'] !== 'number'): ?>
                            <p class="mt-1 text-xs text-gray-500">Unité: <?php echo e($attribute['unit']); ?></p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Les attributs marqués <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Variant</span>
                            seront utilisés pour générer automatiquement les variantes du produit.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\stk\stk-back\resources\views/livewire/product/dynamic-attributes.blade.php ENDPATH**/ ?>