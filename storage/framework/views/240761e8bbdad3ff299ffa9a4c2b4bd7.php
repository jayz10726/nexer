<footer class="site-footer border-t border-gray-100 bg-ink-900 text-gray-300">
    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-4">
            <div>
                <div class="flex items-center gap-2 text-lg font-bold text-white">
                    <img src="<?php echo e(asset('images/nexer-prox-mark.svg')); ?>" alt="NEXER PROX logo" class="h-8 w-8 rounded-lg object-cover ring-1 ring-white/10">
                    <span class="tracking-tight"><?php echo e($siteName); ?></span>
                </div>
                <p class="mt-3 text-sm text-gray-400">Global proxy and virtual number infrastructure for teams that need to move fast, stay private, and scale anywhere.</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Product</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="<?php echo e(route('proxies')); ?>" class="hover:text-white">Proxies</a></li>
                    <li><a href="<?php echo e(route('virtual-numbers')); ?>" class="hover:text-white">Virtual Numbers</a></li>
                    <li><a href="<?php echo e(route('pricing')); ?>" class="hover:text-white">Pricing</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Company</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">About</a></li>
                    <li><a href="mailto:<?php echo e($supportEmail); ?>" class="hover:text-white"><?php echo e($supportEmail); ?></a></li>
                    <li><a href="#" class="hover:text-white">Status</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Legal</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white">Acceptable Use</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 text-sm text-gray-500 md:flex-row">
            <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. All rights reserved.</p>
            <div class="flex gap-4">
                <span>100% Anonymous</span>
                <span>150+ Countries</span>
                <span>99.9% Uptime</span>
                <span>24/7 Support</span>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH /home/onyangozeddy/Desktop/nexerprox/resources/views/partials/footer.blade.php ENDPATH**/ ?>