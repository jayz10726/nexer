<header class="site-nav sticky top-0 z-40 border-b border-gray-100 bg-white/90 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8" aria-label="Main">
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 text-lg font-bold text-ink-900">
            <img src="<?php echo e(asset('images/nexer-prox-mark.svg')); ?>" alt="NEXER PROX logo" class="h-8 w-8 rounded-lg object-cover ring-1 ring-brand-200">
            <span class="tracking-tight"><?php echo e($siteName); ?></span>
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a href="<?php echo e(route('home')); ?>" class="text-sm font-medium text-gray-600 hover:text-brand-600 <?php echo e(request()->routeIs('home') ? 'text-brand-600' : ''); ?>">Home</a>
            <a href="<?php echo e(route('proxies')); ?>" class="text-sm font-medium text-gray-600 hover:text-brand-600 <?php echo e(request()->routeIs('proxies') ? 'text-brand-600' : ''); ?>">Proxies</a>
            <a href="<?php echo e(route('virtual-numbers')); ?>" class="text-sm font-medium text-gray-600 hover:text-brand-600 <?php echo e(request()->routeIs('virtual-numbers') ? 'text-brand-600' : ''); ?>">Virtual Numbers</a>
            <a href="<?php echo e(route('pricing')); ?>" class="text-sm font-medium text-gray-600 hover:text-brand-600 <?php echo e(request()->routeIs('pricing') ? 'text-brand-600' : ''); ?>">Pricing</a>
        </div>

        <div class="hidden items-center gap-3 md:flex">
            <?php if(auth()->guard()->check()): ?>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button type="button" @click="open = !open" class="btn-primary">
                        Buy Now
                        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition class="absolute right-0 z-50 mt-2 w-52 rounded-xl border border-gray-100 bg-white p-2 shadow-lg">
                        <a href="<?php echo e(route('dashboard.proxies.buy')); ?>" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-brand-50 hover:text-brand-700">🌍 Buy a Proxy</a>
                        <a href="<?php echo e(route('dashboard.numbers.buy')); ?>" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-brand-50 hover:text-brand-700">📱 Get a Virtual Number</a>
                    </div>
                </div>
                <a href="<?php echo e(route('dashboard')); ?>" class="text-sm font-medium text-gray-600 hover:text-brand-600 <?php echo e(request()->routeIs('dashboard*') ? 'text-brand-600' : ''); ?>">Dashboard</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-secondary">Log out</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="text-sm font-semibold text-gray-700 hover:text-brand-600">Login</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>

        <button id="mobile-menu-toggle" type="button" class="md:hidden" aria-label="Toggle menu" aria-controls="mobile-menu" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </nav>

    <div id="mobile-menu" class="hidden border-t border-gray-100 px-6 py-4 md:hidden">
        <div class="flex flex-col gap-4">
            <a href="<?php echo e(route('home')); ?>" class="text-sm font-medium text-gray-600">Home</a>
            <a href="<?php echo e(route('proxies')); ?>" class="text-sm font-medium text-gray-600">Proxies</a>
            <a href="<?php echo e(route('virtual-numbers')); ?>" class="text-sm font-medium text-gray-600">Virtual Numbers</a>
            <a href="<?php echo e(route('pricing')); ?>" class="text-sm font-medium text-gray-600">Pricing</a>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard.proxies.buy')); ?>" class="text-sm font-semibold text-brand-600">🌍 Buy a Proxy</a>
                <a href="<?php echo e(route('dashboard.numbers.buy')); ?>" class="text-sm font-semibold text-brand-600">📱 Get a Virtual Number</a>
                <a href="<?php echo e(route('dashboard')); ?>" class="text-sm font-medium text-gray-600">Dashboard</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-sm font-medium text-gray-600">Log out</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="text-sm font-medium text-gray-600">Login</a>
                <a href="<?php echo e(route('register')); ?>" class="text-sm font-semibold text-brand-600">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/partials/nav.blade.php ENDPATH**/ ?>