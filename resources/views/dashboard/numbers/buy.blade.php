@extends('dashboard.layout')

@section('title', 'Buy a Virtual Number — NEXER PROX')

@section('content')
<div x-data="numberBuyer()" x-init="init()">
    <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ink-900">Buy a Virtual Number</h1>
            <p class="mt-1 text-sm text-gray-600">Three quick picks and you're done — country, service, then purchase.</p>
        </div>
        <a href="{{ route('dashboard.numbers') }}" class="btn-secondary shrink-0">← My Numbers</a>
    </div>

    {{-- Progress bar --}}
    <div class="mt-6 flex items-center gap-1">
        <template x-for="n in 4" :key="n">
            <div class="h-1.5 flex-1 rounded-full" :class="step >= n ? 'bg-brand-500' : 'bg-gray-200'"></div>
        </template>
    </div>

    {{-- Completed step summary chips --}}
    <div class="mt-4 flex flex-wrap gap-2" x-show="continent" x-cloak>
        <button type="button" @click="goToStep(1)" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700">
            🌍 <span x-text="continent"></span>
            <span x-show="step > 1" class="text-brand-400">· Change</span>
        </button>
        <button type="button" x-show="country" x-cloak @click="goToStep(2)" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700">
            📍 <span x-text="country"></span>
            <span x-show="step > 2" class="text-brand-400">· Change</span>
        </button>
        <button type="button" x-show="service" x-cloak @click="goToStep(3)" class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700">
            🔧 <span x-text="serviceLabel()"></span>
            <span x-show="step > 3" class="text-brand-400">· Change</span>
        </button>
    </div>

    {{-- Step 1: Continent --}}
    <div class="mt-6" x-show="step === 1" x-cloak>
        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Step 1 — Choose a Continent</h2>
        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            <template x-for="name in Object.keys(continents)" :key="name">
                <button type="button" @click="selectContinent(name)"
                    :data-continent="name.toLowerCase().replace(/ /g, '-')"
                    :style="'--card-index: ' + Object.keys(continents).indexOf(name)"
                    class="continent-card continent-choice cursor-pointer rounded-2xl px-4 py-5 text-sm font-bold"
                    :class="continent === name ? 'is-selected' : ''">
                    <span class="relative z-10 block" x-text="name"></span>
                    <span class="relative z-10 mt-1 block text-xs font-medium opacity-70" x-text="Object.keys(continents[name]).length + ' countries'"></span>
                </button>
            </template>
        </div>
    </div>

    {{-- Step 2: Country --}}
    <div class="mt-6" x-show="step === 2" x-cloak>
        <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Step 2 — Choose a Country in <span x-text="continent"></span></h2>
            <button type="button" @click="goToStep(1)" class="text-xs font-semibold text-brand-600">← Back</button>
        </div>

        <div class="relative mt-3">
            <input type="text" x-model="countrySearch" placeholder="Search countries..."
                class="block w-full rounded-lg border-gray-300 pl-9 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
        </div>

        <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            <template x-for="name in filteredCountries()" :key="name">
                <button type="button" @click="selectCountry(name)"
                    class="rounded-xl border px-4 py-3 text-left text-sm font-medium transition hover:border-brand-400"
                    :class="country === name ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-200 bg-white text-gray-700'">
                    <span x-text="name"></span>
                    <span class="ml-1 text-xs text-gray-400" x-text="'+' + continents[continent][name]"></span>
                </button>
            </template>
            <p class="col-span-full py-4 text-center text-sm text-gray-400" x-show="filteredCountries().length === 0">No countries match "<span x-text="countrySearch"></span>".</p>
        </div>
    </div>

    {{-- Step 3: Service --}}
    <div class="mt-6 space-y-8" x-show="step === 3" x-cloak>
        <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Step 3 — Choose a Service</h2>
            <button type="button" @click="goToStep(2)" class="text-xs font-semibold text-brand-600">← Back</button>
        </div>

        @foreach ($serviceGroups as $groupName => $services)
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $groupName }}</h3>
                <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $slug => $service)
                        <button type="button" @click="selectService('{{ $slug }}')"
                            class="card flex items-center gap-4 text-left transition"
                            :class="service === '{{ $slug }}' ? 'ring-2 ring-brand-600' : ''">
                            @include('dashboard.numbers._icon', ['icon' => $service['icon'], 'color' => $service['color']])
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-ink-900">{{ $service['label'] }}</p>
                                <p class="mt-1 text-sm font-bold" :class="service === '{{ $slug }}' ? 'text-brand-600' : 'text-gray-400'">
                                    <span x-show="service === '{{ $slug }}' && price !== null">$<span x-text="price"></span> / 30 days</span>
                                    <span x-show="service !== '{{ $slug }}'">Select for price</span>
                                </p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Step 4: Purchase --}}
    <div class="mt-6 max-w-xl" x-show="step === 4" x-cloak>
        <div class="flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Step 4 — Confirm &amp; Purchase</h2>
            <button type="button" @click="goToStep(3)" class="text-xs font-semibold text-brand-600">← Back</button>
        </div>

        <div class="order-card mt-3">
            <div class="order-card-header">
                <p class="relative z-10 text-xs font-bold uppercase tracking-[0.18em] text-white/75">Secure checkout</p>
                <h3 class="relative z-10 mt-1 text-2xl font-bold">Order summary</h3>
                <p class="relative z-10 mt-1 text-sm text-white/80">Your number is ready for 30 days.</p>
            </div>
            <dl class="space-y-2 text-sm">
                <div class="order-line"><dt class="text-gray-500">Region</dt><dd class="font-semibold text-ink-900" x-text="continent"></dd></div>
                <div class="order-line"><dt class="text-gray-500">Country</dt><dd class="font-semibold text-ink-900" x-text="country"></dd></div>
                <div class="order-line"><dt class="text-gray-500">Service</dt><dd class="font-semibold text-ink-900" x-text="serviceLabel()"></dd></div>
            </dl>

            <div class="order-total">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-brand-800/70">Total · 30 days</p>
                    <p class="mt-1 text-xs text-brand-900/60">One-time wallet payment</p>
                </div>
                <p class="text-3xl font-extrabold tracking-tight text-brand-800" x-text="price !== null ? '$' + Number(price).toFixed(2) : (loading ? 'Checking…' : '—')"></p>
            </div>

            <div class="order-wallet">
                <span class="text-gray-500">Wallet balance</span>
                <span class="font-bold text-ink-900">${{ number_format(auth()->user()->wallet_balance, 2) }}</span>
            </div>
            <p x-show="price !== null && !insufficientBalance()" class="mt-2 text-right text-xs text-gray-500">
                Balance after purchase: <span class="font-semibold text-ink-900" x-text="'$' + Math.max(0, walletBalance - Number(price)).toFixed(2)"></span>
            </p>

            <div x-show="insufficientBalance()" x-cloak class="order-insufficient" role="alert">
                <p class="font-bold">Insufficient wallet balance.</p>
                <p class="mt-1">Taking you to your wallet to add funds…</p>
                <a href="{{ route('dashboard.wallet') }}" class="mt-2 inline-block font-semibold text-brand-700">Add money to your wallet</a>
            </div>

            <div x-show="priceError" x-cloak class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <p x-text="priceError"></p>
                <button type="button" @click="fetchPrice()" class="mt-2 font-semibold underline">Try again</button>
            </div>

            <form method="POST" action="{{ route('dashboard.numbers.store') }}" class="mt-5">
                @csrf
                <input type="hidden" name="continent" :value="continent">
                <input type="hidden" name="country" :value="country">
                <input type="hidden" name="service" :value="service">
                <button type="submit" class="order-purchase-button" :disabled="loading || price === null || insufficientBalance()" x-text="loading ? 'Checking price…' : 'Confirm & purchase number'">Confirm &amp; purchase number</button>
            </form>
        </div>
    </div>
</div>

<script>
    function numberBuyer() {
        return {
            continents: @json($continents),
            serviceLabels: @json($serviceLabels),
            continent: @js(old('continent', $selectedContinent)),
            country: '{{ old('country', '') }}',
            service: '{{ old('service', '') }}',
            countrySearch: '',
            price: null,
            priceError: '',
            walletBalance: {{ (float) auth()->user()->wallet_balance }},
            loading: false,
            step: 1,
            insufficientRedirectTimer: null,

            insufficientBalance() {
                return this.price !== null && this.walletBalance < Number(this.price);
            },

            redirectToWalletIfInsufficient() {
                if (this.insufficientBalance() && this.insufficientRedirectTimer === null) {
                    this.insufficientRedirectTimer = window.setTimeout(() => {
                        window.location.assign(@js(route('dashboard.wallet')));
                    }, 4000);
                }
            },

            init() {
                if (this.service) {
                    this.step = 4;
                    this.fetchPrice();
                } else if (this.country) {
                    this.step = 3;
                } else if (this.continent) {
                    this.step = 2;
                }
            },

            goToStep(n) {
                this.step = n;
            },

            filteredCountries() {
                const names = this.continent ? Object.keys(this.continents[this.continent]) : [];
                if (!this.countrySearch) return names;
                const q = this.countrySearch.toLowerCase();
                return names.filter(n => n.toLowerCase().includes(q));
            },

            serviceLabel() {
                return this.serviceLabels[this.service] || this.service;
            },

            selectContinent(name) {
                this.continent = name;
                this.country = '';
                this.service = '';
                this.price = null;
                this.countrySearch = '';
                this.step = 2;
            },

            selectCountry(name) {
                this.country = name;
                this.service = '';
                this.price = null;
                this.step = 3;
            },

            selectService(slug) {
                this.service = slug;
                this.step = 4;
                this.fetchPrice();
            },

            fetchPrice() {
                window.clearTimeout(this.insufficientRedirectTimer);
                this.insufficientRedirectTimer = null;
                this.loading = true;
                this.priceError = '';
                const params = new URLSearchParams({ continent: this.continent, country: this.country, service: this.service });
                fetch(`{{ route('dashboard.numbers.price') }}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json' },
                })
                    .then(res => {
                        if (!res.ok) throw new Error('Price request failed');
                        return res.json();
                    })
                    .then(data => {
                        if (typeof data.price !== 'number' || !Number.isFinite(data.price)) {
                            throw new Error('Invalid price response');
                        }
                        this.price = data.price;
                        this.redirectToWalletIfInsufficient();
                    })
                    .catch(() => {
                        this.price = null;
                        this.priceError = 'We could not load the current price. Please try again.';
                    })
                    .finally(() => { this.loading = false; });
            },
        };
    }
</script>
@endsection
