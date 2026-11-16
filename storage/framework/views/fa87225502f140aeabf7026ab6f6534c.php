<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php
    $siteName = \App\Models\SiteSetting::query()->whereKey('site_name')->value('value') ?: 'NEXER PROX';
    $supportEmail = \App\Models\SiteSetting::query()->whereKey('support_email')->value('value') ?: 'support@nexerprox.com';
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="<?php echo e($siteName); ?> provides premium residential, mobile and datacenter proxies plus virtual phone numbers across 150+ countries.">
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('images/nexer-prox-mark.svg')); ?>">
    <title><?php echo e(str_replace('NEXER PROX', $siteName, trim($__env->yieldContent('title', $siteName.' — Global Proxies & Virtual Numbers')))); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="marketing-shell font-sans text-ink-900">
    <?php echo $__env->make('partials.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/layouts/app.blade.php ENDPATH**/ ?>