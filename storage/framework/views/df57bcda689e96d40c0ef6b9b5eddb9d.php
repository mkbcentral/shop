<?php
    use App\Services\MenuService;

    $menuService = app(MenuService::class);
    $user = auth()->user();
    $menusBySection = $menuService->getAccessibleMenusForUser($user);
?>

<?php if (isset($component)) { $__componentOriginal2880b66d47486b4bfeaf519598a469d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2880b66d47486b4bfeaf519598a469d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="space-y-1">
        
        <?php if($menusBySection->has('no_section')): ?>
            <?php $__currentLoopData = $menusBySection->get('no_section'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($menu->children->isEmpty()): ?>
                    
                    <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e($menu->route ? route($menu->route) : ($menu->url ?? '#')).'','active' => $menu->route ? request()->routeIs($menu->route) : false,'icon' => $menu->icon,'badge' => $menu->badge_type === 'text' ? ($menu->badge_value ?? null) : null,'badgeColor' => $menu->badge_color]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($menu->route ? route($menu->route) : ($menu->url ?? '#')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->route ? request()->routeIs($menu->route) : false),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->icon),'badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_type === 'text' ? ($menu->badge_value ?? null) : null),'badgeColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_color)]); ?>
                        <?php echo e($menu->name); ?>

                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
                <?php else: ?>
                    
                    <?php if (isset($component)) { $__componentOriginalcd06974277d3867b1546f1effcc38030 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd06974277d3867b1546f1effcc38030 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-dropdown','data' => ['title' => ''.e($menu->name).'','badge' => $menu->badge_type === 'count' ? (${$menu->code . '_count'} ?? 0) : null,'badgeColor' => $menu->badge_color,'activePattern' => ''.e($menu->code).'/*','icon' => $menu->icon]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($menu->name).'','badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_type === 'count' ? (${$menu->code . '_count'} ?? 0) : null),'badgeColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_color),'activePattern' => ''.e($menu->code).'/*','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->icon)]); ?>

                        <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e($child->route ? route($child->route) : ($child->url ?? '#')).'','active' => $child->route ? request()->routeIs($child->route) : false,'isDropdownItem' => true,'badge' => $child->badge_type === 'count' ? (${$child->code . '_count'} ?? null) : null,'badgeColor' => $child->badge_color,'animate' => $child->badge_type === 'count' && isset(${$child->code . '_count'}) && ${$child->code . '_count'} > 0]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($child->route ? route($child->route) : ($child->url ?? '#')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->route ? request()->routeIs($child->route) : false),'isDropdownItem' => true,'badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->badge_type === 'count' ? (${$child->code . '_count'} ?? null) : null),'badgeColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->badge_color),'animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->badge_type === 'count' && isset(${$child->code . '_count'}) && ${$child->code . '_count'} > 0)]); ?>
                                <?php echo e($child->name); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd06974277d3867b1546f1effcc38030)): ?>
<?php $attributes = $__attributesOriginalcd06974277d3867b1546f1effcc38030; ?>
<?php unset($__attributesOriginalcd06974277d3867b1546f1effcc38030); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd06974277d3867b1546f1effcc38030)): ?>
<?php $component = $__componentOriginalcd06974277d3867b1546f1effcc38030; ?>
<?php unset($__componentOriginalcd06974277d3867b1546f1effcc38030); ?>
<?php endif; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        
        <?php $__currentLoopData = $menusBySection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionName => $sectionMenus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($sectionName !== 'no_section' && $sectionMenus->isNotEmpty()): ?>
                <?php if (isset($component)) { $__componentOriginal19b3b58260c06b73d31092a7043ca4e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal19b3b58260c06b73d31092a7043ca4e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-section','data' => ['title' => ''.e($sectionName).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($sectionName).'']); ?>
                    <?php $__currentLoopData = $sectionMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($menu->children->isEmpty()): ?>
                            
                            <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e($menu->route ? route($menu->route) : ($menu->url ?? '#')).'','active' => $menu->route ? request()->routeIs($menu->route . '*') : false,'icon' => $menu->icon,'badge' => $menu->badge_type === 'text' ? ($menu->badge_value ?? 'POS') : null,'badgeColor' => $menu->badge_color,'animate' => $menu->badge_type === 'text']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($menu->route ? route($menu->route) : ($menu->url ?? '#')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->route ? request()->routeIs($menu->route . '*') : false),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->icon),'badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_type === 'text' ? ($menu->badge_value ?? 'POS') : null),'badgeColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_color),'animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_type === 'text')]); ?>
                                <?php echo e($menu->name); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
                        <?php else: ?>
                            
                            <?php if (isset($component)) { $__componentOriginalcd06974277d3867b1546f1effcc38030 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd06974277d3867b1546f1effcc38030 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-dropdown','data' => ['title' => ''.e($menu->name).'','badge' => $menu->badge_type === 'count' ? (${$menu->code . '_count'} ?? (${'total_' . $menu->code} ?? 0)) : null,'badgeColor' => $menu->badge_color,'activePattern' => ''.e($menu->code).'/*','icon' => $menu->icon]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => ''.e($menu->name).'','badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_type === 'count' ? (${$menu->code . '_count'} ?? (${'total_' . $menu->code} ?? 0)) : null),'badgeColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->badge_color),'activePattern' => ''.e($menu->code).'/*','icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($menu->icon)]); ?>

                                <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if (isset($component)) { $__componentOriginal5bfd3bb159ce0000260348a653d76773 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5bfd3bb159ce0000260348a653d76773 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar-item','data' => ['href' => ''.e($child->route ? route($child->route) : ($child->url ?? '#')).'','active' => $child->route ? request()->routeIs($child->route) : false,'isDropdownItem' => true,'badge' => $child->badge_type === 'count' ? (${$child->code . '_count'} ?? ($low_stock_alerts ?? null)) : null,'badgeColor' => $child->badge_color,'animate' => $child->badge_color === 'red' && isset($low_stock_alerts) && $low_stock_alerts > 0]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e($child->route ? route($child->route) : ($child->url ?? '#')).'','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->route ? request()->routeIs($child->route) : false),'isDropdownItem' => true,'badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->badge_type === 'count' ? (${$child->code . '_count'} ?? ($low_stock_alerts ?? null)) : null),'badgeColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->badge_color),'animate' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($child->badge_color === 'red' && isset($low_stock_alerts) && $low_stock_alerts > 0)]); ?>
                                        <?php echo e($child->name); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $attributes = $__attributesOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__attributesOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5bfd3bb159ce0000260348a653d76773)): ?>
<?php $component = $__componentOriginal5bfd3bb159ce0000260348a653d76773; ?>
<?php unset($__componentOriginal5bfd3bb159ce0000260348a653d76773); ?>
<?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd06974277d3867b1546f1effcc38030)): ?>
<?php $attributes = $__attributesOriginalcd06974277d3867b1546f1effcc38030; ?>
<?php unset($__attributesOriginalcd06974277d3867b1546f1effcc38030); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd06974277d3867b1546f1effcc38030)): ?>
<?php $component = $__componentOriginalcd06974277d3867b1546f1effcc38030; ?>
<?php unset($__componentOriginalcd06974277d3867b1546f1effcc38030); ?>
<?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal19b3b58260c06b73d31092a7043ca4e9)): ?>
<?php $attributes = $__attributesOriginal19b3b58260c06b73d31092a7043ca4e9; ?>
<?php unset($__attributesOriginal19b3b58260c06b73d31092a7043ca4e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal19b3b58260c06b73d31092a7043ca4e9)): ?>
<?php $component = $__componentOriginal19b3b58260c06b73d31092a7043ca4e9; ?>
<?php unset($__componentOriginal19b3b58260c06b73d31092a7043ca4e9); ?>
<?php endif; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $attributes = $__attributesOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $component = $__componentOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__componentOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
<?php /**PATH D:\stk\stk-back\resources\views/components/navigation-dynamic.blade.php ENDPATH**/ ?>