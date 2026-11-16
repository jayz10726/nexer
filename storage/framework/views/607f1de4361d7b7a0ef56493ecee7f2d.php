<?php $__env->startSection('title', 'Wallet — NEXER PROX'); ?>

<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-bold text-ink-900">Wallet</h1>

<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="card lg:col-span-1">
        <p class="text-sm text-gray-500">Current Balance</p>
        <p class="mt-2 text-3xl font-bold gradient-text">$<?php echo e(number_format($walletBalance, 2)); ?></p>

        <form method="POST" action="<?php echo e(route('dashboard.wallet.topup')); ?>" class="mt-6 space-y-3" x-data="{ amount: '', paymentMethod: 'card', rate: <?php echo e(config('currency.paystack.usd_rate')); ?> }">
            <?php echo csrf_field(); ?>
            <label class="block text-sm font-medium text-gray-700">Quick top up</label>
            <div class="grid grid-cols-4 gap-2">
                <?php $__currentLoopData = [5, 10, 25, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" @click="amount = '<?php echo e($preset); ?>'"
                        class="rounded-lg border px-2 py-2 text-sm font-semibold transition"
                        :class="amount === '<?php echo e($preset); ?>' ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-200 text-gray-600 hover:border-brand-300'">
                        $<?php echo e($preset); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <label for="amount" class="block text-sm font-medium text-gray-700">Or enter a custom amount (USD)</label>
            <input type="number" step="0.01" min="5" name="amount" id="amount" required x-model="amount"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                placeholder="e.g. 25.00">
            <p x-show="Number(amount) >= 5" x-cloak class="text-xs text-gray-500">
                You will pay approximately <span class="font-semibold text-ink-900" x-text="'<?php echo e(config('currency.paystack.code')); ?> ' + (Number(amount) * rate).toFixed(2)"></span> at the current rate of $1 = <?php echo e(config('currency.paystack.code')); ?> <?php echo e(number_format(config('currency.paystack.usd_rate'), 2)); ?>.
            </p>
            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <label for="payment_method" class="block text-sm font-medium text-gray-700">Choose how to pay</label>
            <select name="payment_method" id="payment_method" required x-model="paymentMethod"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                <option value="card">Card</option>
                <option value="bank">Bank account</option>
                <option value="apple_pay">Apple Pay</option>
                <option value="ussd">USSD</option>
                <option value="qr">QR code</option>
                <option value="mobile_money">Mobile money</option>
                <option value="bank_transfer">Bank transfer</option>
            </select>
            <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['payment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="rounded-lg bg-red-50 p-3 text-sm text-red-700"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <button type="submit" class="btn-primary w-full">Continue to secure payment</button>
            <p class="text-xs text-gray-400">Paystack will securely collect your payment details and PIN when required. Your details never pass through this app.</p>
        </form>
    </div>

    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Recent Activity</h2>
            <a href="<?php echo e(route('dashboard.transactions')); ?>" class="text-sm font-semibold text-brand-600">View all</a>
        </div>

        <div class="mt-4 divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between py-3">
                    <div>
                        <p class="text-sm font-medium text-ink-900"><?php echo e($transaction->description ?? ucfirst($transaction->type)); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($transaction->created_at->format('M j, Y · g:i A')); ?></p>
                    </div>
                    <span class="text-sm font-semibold <?php echo e(in_array($transaction->type, ['topup', 'bonus']) ? 'text-forest-600' : 'text-gray-700'); ?>">
                        <?php echo e(in_array($transaction->type, ['topup', 'bonus']) ? '+' : '-'); ?>$<?php echo e(number_format($transaction->amount, 2)); ?>

                    </span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="py-6 text-center text-sm text-gray-500">No wallet activity yet.</p>
            <?php endif; ?>
        </div>

        <div class="mt-4"><?php echo e($transactions->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/dashboard/wallet/index.blade.php ENDPATH**/ ?>