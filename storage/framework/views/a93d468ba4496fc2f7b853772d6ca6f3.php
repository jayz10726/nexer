<?php $__env->startSection('title', 'Pricing — NEXER PROX'); ?>

<?php $__env->startSection('content'); ?>
<section class="bg-gradient-to-b from-brand-50 to-white">
    <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
        <p class="section-eyebrow">Pricing</p>
        <h1 class="mt-4 text-4xl font-extrabold text-ink-900 sm:text-5xl">Flexible Plans for Every Stage</h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600">Choose the plan that fits your business today. Upgrade any time as you grow.</p>
    </div>
</section>


<section class="mx-auto max-w-4xl px-6 lg:px-8">
    <div class="card flex flex-col items-center gap-4 text-center sm:flex-row sm:text-left">
        <span class="brand-mark shrink-0 text-lg">💡</span>
        <div class="flex-1">
            <p class="text-sm font-semibold text-ink-900">Just need one proxy or number, not a plan?</p>
            <p class="mt-1 text-sm text-gray-600">Skip the plans below — buy exactly what you need, priced individually, from your dashboard.</p>
        </div>
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('dashboard.proxies.buy')); ?>" class="btn-primary shrink-0">Buy Individually</a>
        <?php else: ?>
            <a href="<?php echo e(route('register')); ?>" class="btn-primary shrink-0">Sign Up &amp; Buy</a>
        <?php endif; ?>
    </div>
</section>

<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card flex flex-col <?php echo e($plan->is_featured ? 'ring-2 ring-brand-600' : ''); ?>">
                <?php if($plan->is_featured): ?>
                    <span class="mb-3 inline-block w-fit rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold text-brand-700">Most Popular</span>
                <?php endif; ?>
                <h3 class="text-lg font-semibold"><?php echo e($plan->name); ?></h3>
                <p class="mt-2 text-3xl font-bold text-ink-900">
                    <?php if($plan->is_custom): ?>
                        Custom
                    <?php else: ?>
                        $<?php echo e(number_format($plan->price, 0)); ?><span class="text-base font-medium text-gray-500">/<?php echo e($plan->billing_period); ?></span>
                    <?php endif; ?>
                </p>
                <ul class="mt-6 flex-1 space-y-3 text-sm text-gray-700">
                    <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-brand-600">✓</span>
                            <span><?php echo e($feature); ?></span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <a href="<?php echo e(route('register')); ?>" class="<?php echo e($plan->is_featured ? 'btn-primary' : 'btn-secondary'); ?> mt-8"><?php echo e($plan->cta_label); ?></a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="col-span-3 text-center text-gray-500">Plans haven't been seeded yet — run <code>php artisan db:seed</code>.</p>
        <?php endif; ?>
    </div>
</section>

<section class="bg-ink-900 text-white">
    <div class="mx-auto max-w-7xl px-6 py-10 text-center text-sm text-gray-300 lg:px-8">
        100% Anonymous · 150+ Countries · 99.9% Uptime · 24/7 Support
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/pricing.blade.php ENDPATH**/ ?>