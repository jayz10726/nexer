<footer class="site-footer border-t border-gray-100 bg-ink-900 text-gray-300">
    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-4">
            <div>
                <div class="flex min-w-0 items-center gap-2 text-lg font-bold text-white">
                    <img src="{{ asset('images/nexer-prox-mark.svg') }}" alt="NEXER PROX logo" class="h-8 w-8 shrink-0 rounded-lg object-cover ring-1 ring-white/10">
                    <span class="truncate tracking-tight">{{ $siteName }}</span>
                </div>
                <p class="mt-3 text-sm text-gray-400">Global proxy and virtual number infrastructure for teams that need to move fast, stay private, and scale anywhere.</p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Product</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="{{ route('proxies') }}" class="hover:text-white">Proxies</a></li>
                    <li><a href="{{ route('virtual-numbers') }}" class="hover:text-white">Virtual Numbers</a></li>
                    <li><a href="{{ route('pricing') }}" class="hover:text-white">Pricing</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-white">Company</h3>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white">About</a></li>
                    <li><a href="mailto:{{ $supportEmail }}" class="break-all hover:text-white">{{ $supportEmail }}</a></li>
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
            <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 md:flex-nowrap">
                <span>100% Anonymous</span>
                <span>150+ Countries</span>
                <span>99.9% Uptime</span>
                <span>24/7 Support</span>
            </div>
        </div>
    </div>
</footer>
