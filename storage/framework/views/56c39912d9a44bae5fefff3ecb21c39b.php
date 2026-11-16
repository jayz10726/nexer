<?php $__env->startSection('title', 'Log In — NEXER PROX'); ?>

<?php $__env->startSection('content'); ?>
<section class="auth-page mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-6 py-16">
    <h1 class="text-2xl font-bold text-ink-900">Welcome back</h1>
    <p class="mt-2 text-sm text-gray-600">Log in to manage your proxies and virtual numbers.</p>

    <?php $__errorArgs = ['google'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <?php if(session('status')): ?>
        <div class="mt-4 rounded-lg bg-forest-50 p-3 text-sm text-forest-700"><?php echo e(session('status')); ?></div>
    <?php endif; ?>

    <a href="<?php echo e(route('auth.google')); ?>" class="mt-8 flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
        <svg class="h-5 w-5" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M23.52 12.27c0-.79-.07-1.54-.2-2.27H12v4.51h6.47c-.28 1.5-1.13 2.77-2.41 3.62v3.01h3.87c2.26-2.09 3.59-5.17 3.59-8.87z"/>
            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.93-2.9l-3.87-3.01c-1.07.72-2.44 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.27v3.11C3.24 21.3 7.29 24 12 24z"/>
            <path fill="#FBBC05" d="M5.27 14.28A7.14 7.14 0 0 1 4.9 12c0-.79.14-1.56.37-2.28V6.61H1.27A11.93 11.93 0 0 0 0 12c0 1.93.46 3.76 1.27 5.39l4-3.11z"/>
            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.43-3.43C17.94 1.19 15.24 0 12 0 7.29 0 3.24 2.7 1.27 6.61l4 3.11C6.22 6.86 8.87 4.75 12 4.75z"/>
        </svg>
        Continue with Google
    </a>

    <div class="mt-6 flex items-center gap-3">
        <span class="h-px flex-1 bg-gray-200"></span>
        <span class="text-xs uppercase text-gray-400">or</span>
        <span class="h-px flex-1 bg-gray-200"></span>
    </div>

    <form method="POST" action="<?php echo e(route('login')); ?>" class="mt-6 space-y-5">
        <?php echo csrf_field(); ?>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" name="password" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                Remember me
            </label>
        </div>

        <button type="submit" class="btn-primary w-full">Log In</button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Don't have an account? <a href="<?php echo e(route('register')); ?>" class="font-semibold text-brand-600">Sign up</a>
    </p>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/auth/login.blade.php ENDPATH**/ ?>