<?php $__env->startSection('title', 'Virtual Numbers — NEXER PROX'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-ink-900">Virtual Numbers</h1>
    <a href="<?php echo e(route('dashboard.numbers.buy')); ?>" class="btn-primary">Get a Number</a>
</div>

<section class="inventory-section mt-6">
    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Browse numbers by continent</h2>
    <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        <?php $__currentLoopData = $continents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $continent => $countries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('dashboard.numbers.buy', ['continent' => $continent])); ?>"
               data-continent="<?php echo e(\Illuminate\Support\Str::slug($continent)); ?>"
               style="--card-index: <?php echo e($loop->index); ?>"
               class="continent-card rounded-2xl px-4 py-5 text-sm font-bold">
                <span class="relative z-10 block"><?php echo e($continent); ?></span>
                <span class="relative z-10 mt-1 block text-xs font-medium opacity-70"><?php echo e(count($countries)); ?> countries</span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<div class="inventory-section mt-6 overflow-x-auto rounded-xl border border-gray-100 bg-white">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
            <tr>
                <th class="px-5 py-3">Number</th>
                <th class="px-5 py-3">Country</th>
                <th class="px-5 py-3">Service</th>
                <th class="px-5 py-3">Price</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Expires</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $number): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-5 py-4 font-mono text-xs"><?php echo e($number->phone_number); ?></td>
                    <td class="px-5 py-4"><?php echo e($number->country); ?><?php if($number->continent): ?><span class="text-gray-400"> · <?php echo e($number->continent); ?></span><?php endif; ?></td>
                    <td class="px-5 py-4"><?php echo e($number->service ? \App\Support\ServiceCatalog::label($number->service) : '—'); ?></td>
                    <td class="px-5 py-4"><?php echo e($number->price ? '$'.number_format($number->price, 2) : '—'); ?></td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2 py-1 text-xs font-semibold <?php echo e($number->status === 'active' ? 'bg-forest-100 text-forest-700' : 'bg-gray-100 text-gray-600'); ?>">
                            <?php echo e(ucfirst($number->status)); ?>

                        </span>
                    </td>
                    <td class="px-5 py-4"><?php echo e($number->expires_at?->format('M j, Y') ?? '—'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">You don't have any virtual numbers yet. <a href="<?php echo e(route('dashboard.numbers.buy')); ?>" class="font-semibold text-brand-600">Get your first one</a>.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-6"><?php echo e($numbers->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/dashboard/numbers/index.blade.php ENDPATH**/ ?>