<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'ShopFlow')); ?> - <?php echo $__env->yieldContent('title', 'Authentification'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30">
    <div class="min-h-screen">
        <!-- Main Content with Two Columns -->
        <?php echo e($slot); ?>

    </div>
</body>
</html>
<?php /**PATH D:\stk\stk-back\resources\views/components/layouts/guest.blade.php ENDPATH**/ ?>