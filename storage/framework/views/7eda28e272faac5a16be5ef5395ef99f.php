<?php $__env->startSection('title', 'Virtual Numbers — NEXER PROX'); ?>

<?php $__env->startSection('content'); ?>
<section class="bg-gradient-to-b from-brand-50 to-white">
    <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
        <p class="section-eyebrow">Virtual numbers</p>
        <h1 class="mt-4 text-4xl font-extrabold text-ink-900 sm:text-5xl">Local Numbers for Global Business</h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600">Get local numbers from 150+ countries for SMS, calls, and account verification, activated in minutes.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="card">
            <h3 class="text-lg font-semibold">+1 United States</h3>
            <p class="mt-2 text-sm text-gray-600">Local and toll-free numbers with coverage across all 50 states.</p>
            <a href="<?php echo e(auth()->check() ? route('dashboard.numbers.buy') : route('register')); ?>" class="mt-4 inline-block text-sm font-semibold text-brand-600">Get Number →</a>
        </div>
        <div class="card">
            <h3 class="text-lg font-semibold">+44 United Kingdom</h3>
            <p class="mt-2 text-sm text-gray-600">Numbers covering London, Manchester, and other major UK cities.</p>
            <a href="<?php echo e(auth()->check() ? route('dashboard.numbers.buy') : route('register')); ?>" class="mt-4 inline-block text-sm font-semibold text-brand-600">Get Number →</a>
        </div>
        <div class="card">
            <h3 class="text-lg font-semibold">+91 India</h3>
            <p class="mt-2 text-sm text-gray-600">Coverage across major Indian cities for SMS and voice verification.</p>
            <a href="<?php echo e(auth()->check() ? route('dashboard.numbers.buy') : route('register')); ?>" class="mt-4 inline-block text-sm font-semibold text-brand-600">Get Number →</a>
        </div>
    </div>
</section>

<section class="bg-gray-50">
    <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
        <h2 class="text-2xl font-bold text-ink-900">150+ Countries, One Dashboard</h2>
        <p class="mx-auto mt-3 max-w-2xl text-gray-600">Manage numbers across Europe, Asia, the Americas, and Africa from a single account.</p>
        <a href="<?php echo e(auth()->check() ? route('dashboard.numbers.buy') : route('register')); ?>" class="btn-primary mt-8"><?php echo e(auth()->check() ? 'Get a Number Now' : 'Sign Up & Buy'); ?></a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/virtual-numbers.blade.php ENDPATH**/ ?>