@extends('layouts.app')

@section('title', 'NEXER PROX — Global Proxies & Virtual Numbers')

@section('content')
{{-- Hero --}}
<section class="home-hero relative isolate overflow-hidden">
    <div class="home-hero-grid absolute inset-0" aria-hidden="true"></div>
    <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-6 py-16 sm:py-20 lg:grid-cols-[0.9fr_1.1fr] lg:px-8 lg:py-24">
        <div class="home-hero-copy">
            <p class="home-hero-eyebrow"><span class="home-live-dot"></span> Global infrastructure. Ready when you are.</p>
            <h1 class="mt-6 max-w-2xl text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                Move through the internet <span class="home-hero-highlight">without borders.</span>
            </h1>
            <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                Find the right proxy or local number for every market. Choose a location, see your price upfront, and get started in minutes.
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                @auth
                    <a href="{{ route('dashboard.proxies.buy') }}" class="home-hero-primary">Explore proxies <span aria-hidden="true">→</span></a>
                    <a href="{{ route('dashboard.numbers.buy') }}" class="home-hero-secondary">Get a virtual number</a>
                @else
                    <a href="{{ route('register') }}" class="home-hero-primary">Get started <span aria-hidden="true">→</span></a>
                    <a href="{{ route('pricing') }}" class="home-hero-secondary">Explore pricing</a>
                @endauth
            </div>
            <div class="mt-9 flex flex-wrap items-center gap-x-5 gap-y-3 text-xs font-medium text-slate-300">
                <span class="inline-flex items-center gap-2"><span class="home-check">✓</span> Instant price preview</span>
                <span class="inline-flex items-center gap-2"><span class="home-check">✓</span> Pay from your wallet</span>
                <span class="inline-flex items-center gap-2"><span class="home-check">✓</span> No long-term contracts</span>
            </div>
            <dl class="mt-12 grid max-w-lg grid-cols-3 gap-5 border-t border-white/10 pt-6">
                <div><dt class="text-2xl font-extrabold text-white sm:text-3xl">150<span class="text-cyan-300">+</span></dt><dd class="mt-1 text-xs text-slate-400 sm:text-sm">Countries</dd></div>
                <div><dt class="text-2xl font-extrabold text-white sm:text-3xl">99.9<span class="text-cyan-300">%</span></dt><dd class="mt-1 text-xs text-slate-400 sm:text-sm">Uptime</dd></div>
                <div><dt class="text-2xl font-extrabold text-white sm:text-3xl">24<span class="text-cyan-300">/7</span></dt><dd class="mt-1 text-xs text-slate-400 sm:text-sm">Support</dd></div>
            </dl>
        </div>

        <div class="home-network-visual" aria-label="Illustration of a connected global proxy network">
            <div class="home-network-glow"></div>
            <svg class="home-world-map" viewBox="0 0 820 440" role="img" aria-labelledby="world-map-title">
                <title id="world-map-title">Stylized world map with connected network locations</title>
                <defs>
                    <linearGradient id="map-land" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#35D6F4" stop-opacity=".28"/>
                        <stop offset="1" stop-color="#806BFF" stop-opacity=".16"/>
                    </linearGradient>
                    <linearGradient id="map-route" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0" stop-color="#31D7F5"/>
                        <stop offset="1" stop-color="#9B8CFF"/>
                    </linearGradient>
                    <pattern id="map-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M40 0H0V40" fill="none" stroke="#B6D7FF" stroke-opacity=".08" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="820" height="440" fill="url(#map-grid)"/>
                <g class="map-land" fill="url(#map-land)" stroke="#82E9FA" stroke-opacity=".32" stroke-width="1.5">
                    <path d="M92 93 125 64 172 55 204 72 236 69 258 94 247 113 267 127 252 147 230 150 220 171 194 179 177 205 151 211 137 190 116 183 110 158 89 146 76 119Z"/>
                    <path d="m219 205 24 11 15 29-8 26 15 29-14 40-14 30-17-16-5-34-13-24 6-30-13-26Z"/>
                    <path d="m350 96 24-19 35 5 17 20-12 17-28 3-10 20-23-8-17-19Z"/>
                    <path d="m367 149 28-14 34 8 21 29-11 38-22 31-20 45-25-9-8-35-16-28 7-32Z"/>
                    <path d="m432 101 26-23 42 3 19-16 58 11 29 24 47 7 28 24-17 20-45-3-24 18-34-7-21 17-34-12-19 12-20-18-32 4-20-22-29-3Z"/>
                    <path d="m613 238 28-14 28 13 8 25-21 20-32-8-17-18Z"/>
                    <path d="m695 302 24-8 23 14-8 21-27 4-18-14Z"/>
                </g>
                <g class="map-routes" fill="none" stroke="url(#map-route)" stroke-width="2">
                    <path d="M177 145 Q285 48 390 168"/>
                    <path d="M390 168 Q493 77 553 134"/>
                    <path d="M390 168 Q399 265 388 302"/>
                    <path d="M390 168 Q523 194 652 252"/>
                    <path d="M177 145 Q275 212 388 302"/>
                    <path d="M553 134 Q594 178 652 252"/>
                </g>
                <g class="map-nodes">
                    <circle cx="177" cy="145" r="5"/><circle cx="390" cy="168" r="6"/>
                    <circle cx="553" cy="134" r="5"/><circle cx="388" cy="302" r="5"/>
                    <circle cx="652" cy="252" r="5"/><circle cx="263" cy="251" r="4"/>
                </g>
                <g class="map-node-rings">
                    <circle cx="177" cy="145" r="13"/><circle cx="390" cy="168" r="16"/>
                    <circle cx="553" cy="134" r="13"/><circle cx="388" cy="302" r="13"/>
                    <circle cx="652" cy="252" r="13"/>
                </g>
            </svg>
            <div class="home-network-label home-network-label-top"><span class="home-live-dot"></span><span><strong>Network status</strong><small>All regions operational</small></span></div>
            <div class="home-network-label home-network-label-bottom"><span class="home-network-icon">⌁</span><span><strong>One dashboard</strong><small>Proxy access across the globe</small></span></div>
            <div class="home-network-tag">GLOBAL ROUTING <span>● LIVE</span></div>
        </div>
    </div>
</section>

{{-- How it works --}}
<section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="text-center">
        <p class="section-eyebrow">Simple by design</p>
        <h2 class="mt-2 text-3xl font-bold text-ink-900">Buying Takes Less Than a Minute</h2>
        <p class="mx-auto mt-3 max-w-2xl text-gray-600">No forms, no waiting on a sales rep. Pick, price, purchase — right from your dashboard.</p>
    </div>

    <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <div class="text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full text-lg font-bold text-white" style="background-image: linear-gradient(135deg, #00BFFF 0%, #6F2DA8 100%);">1</div>
            <h3 class="mt-4 text-sm font-semibold text-ink-900">Pick a Country</h3>
            <p class="mt-1 text-sm text-gray-500">Choose from 6 continents and 190+ countries.</p>
        </div>
        <div class="text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full text-lg font-bold text-white" style="background-image: linear-gradient(135deg, #00BFFF 0%, #6F2DA8 100%);">2</div>
            <h3 class="mt-4 text-sm font-semibold text-ink-900">Pick a Type or Service</h3>
            <p class="mt-1 text-sm text-gray-500">Residential, mobile, datacenter, or ISP proxies — or the exact platform you need a number for.</p>
        </div>
        <div class="text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full text-lg font-bold text-white" style="background-image: linear-gradient(135deg, #00BFFF 0%, #6F2DA8 100%);">3</div>
            <h3 class="mt-4 text-sm font-semibold text-ink-900">See the Price Instantly</h3>
            <p class="mt-1 text-sm text-gray-500">Transparent pricing shown before you commit — no surprises at checkout.</p>
        </div>
        <div class="text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full text-lg font-bold text-white" style="background-image: linear-gradient(135deg, #00BFFF 0%, #6F2DA8 100%);">4</div>
            <h3 class="mt-4 text-sm font-semibold text-ink-900">Purchase from Your Wallet</h3>
            <p class="mt-1 text-sm text-gray-500">One click, funded by your NEXER PROX wallet. Ready to use right away.</p>
        </div>
    </div>

    <div class="mt-10 text-center">
        @auth
            <a href="{{ route('dashboard.proxies.buy') }}" class="btn-primary">Start Buying →</a>
        @else
            <a href="{{ route('register') }}" class="btn-primary">Create Your Free Account →</a>
        @endauth
    </div>
</section>

{{-- Infrastructure highlights --}}
<section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="text-center">
        <p class="section-eyebrow">Under the hood</p>
        <h2 class="mt-2 text-3xl font-bold text-ink-900">Infrastructure You Can Rely On</h2>
    </div>

    <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="card">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12h18M3 6h18M3 18h18" /></svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold">Distributed Data Centers</h3>
            <p class="mt-2 text-sm text-gray-600">Traffic routes through a wide network of points of presence spread across every populated continent.</p>
        </div>
        <div class="card">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z" /></svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold">End-to-End Encryption</h3>
            <p class="mt-2 text-sm text-gray-600">Every session is encrypted in transit, so your traffic and verification codes stay private by default.</p>
        </div>
        <div class="card">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 text-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold">Real Human Support</h3>
            <p class="mt-2 text-sm text-gray-600">A dedicated team is on call around the clock to help with setup, integrations, and troubleshooting.</p>
        </div>
    </div>
</section>

{{-- Proxy feature block --}}
<section class="bg-gray-50">
    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 py-20 lg:grid-cols-2 lg:px-8">
        <div>
            <p class="section-eyebrow">Proxy network</p>
            <h2 class="mt-2 text-3xl font-bold text-ink-900">Enterprise-Grade Proxy Access</h2>
            <p class="mt-4 text-gray-600">Pull from a large pool of residential, mobile, and datacenter IPs with automated rotation and no throttling.</p>
            <ul class="mt-6 space-y-3 text-sm text-gray-700">
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> Unlimited concurrent sessions</li>
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> Automatic IP rotation</li>
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> No hidden bandwidth caps</li>
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> Full API for automation</li>
            </ul>
            <a href="{{ route('proxies') }}" class="mt-8 inline-block font-semibold text-brand-600 hover:text-brand-700">Explore Proxies →</a>
        </div>
        <div class="feature-visual feature-visual-proxy aspect-video overflow-hidden rounded-2xl p-6 sm:p-8">
            <div class="feature-visual-content flex h-full flex-col justify-between">
                <div class="flex items-center justify-between text-white">
                    <span class="text-sm font-semibold tracking-wide">LIVE NETWORK</span>
                    <span class="flex items-center gap-2 text-xs text-white/80"><span class="h-2 w-2 rounded-full bg-emerald-300"></span>Operational</span>
                </div>
                <div class="grid grid-cols-3 gap-3 text-white">
                    <div class="rounded-xl border border-white/20 bg-white/10 p-3 backdrop-blur-sm"><p class="text-xs text-white/70">Regions</p><p class="mt-1 text-xl font-bold">150+</p></div>
                    <div class="rounded-xl border border-white/20 bg-white/10 p-3 backdrop-blur-sm"><p class="text-xs text-white/70">Latency</p><p class="mt-1 text-xl font-bold">&lt;60ms</p></div>
                    <div class="rounded-xl border border-white/20 bg-white/10 p-3 backdrop-blur-sm"><p class="text-xs text-white/70">Uptime</p><p class="mt-1 text-xl font-bold">99.9%</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Virtual numbers feature block --}}
<section>
    <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 py-20 lg:grid-cols-2 lg:px-8">
        <div class="feature-visual feature-visual-number number-demo-visual order-2 aspect-video overflow-hidden rounded-2xl p-6 sm:p-8 lg:order-1">
            <div class="feature-visual-content flex h-full flex-col justify-between">
                <div class="flex items-center justify-between text-white">
                    <span class="text-sm font-semibold tracking-wide">YOUR NUMBER</span>
                    <span class="rounded-full bg-white/15 px-3 py-1 text-xs text-white/80">SMS READY</span>
                </div>
                <div class="number-demo-card rounded-2xl border border-white/20 bg-white/10 p-5 text-white backdrop-blur-sm">
                    <p class="text-xs uppercase tracking-widest text-white/60">United Kingdom</p>
                    <p class="number-demo-value mt-2 text-2xl font-bold tracking-wide sm:text-3xl" aria-label="+44 20 7946 0188">
                        <span aria-hidden="true">+44</span><span aria-hidden="true">20</span><span aria-hidden="true">7946</span><span aria-hidden="true">0188</span>
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-xs text-white/75"><span class="h-2 w-2 rounded-full bg-cyan-300"></span>Ready to receive verification codes</div>
                </div>
            </div>
        </div>
        <div class="order-1 lg:order-2">
            <p class="section-eyebrow">Virtual numbers</p>
            <h2 class="mt-2 text-3xl font-bold text-ink-900">A Local Number, Anywhere</h2>
            <p class="mt-4 text-gray-600">Stand up a local presence in minutes with numbers that support SMS and voice, forwarded to any device you choose.</p>
            <ul class="mt-6 space-y-3 text-sm text-gray-700">
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> SMS and voice capable</li>
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> Forward to any device</li>
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> No long-term contracts</li>
                <li class="flex items-center gap-2"><span class="text-brand-600">✓</span> Simple pay-as-you-go pricing</li>
            </ul>
            <a href="{{ route('virtual-numbers') }}" class="mt-8 inline-block font-semibold text-brand-600 hover:text-brand-700">Get Numbers →</a>
        </div>
    </div>
</section>

{{-- Trust bar --}}
<section class="bg-ink-900 text-white">
    <div class="mx-auto max-w-7xl px-6 py-16 text-center lg:px-8">
        <h2 class="text-2xl font-bold">Trusted by Fast-Moving Teams Worldwide</h2>
        <p class="mt-2 text-gray-400">Businesses of every size rely on NEXER PROX for their proxy and virtual number needs.</p>
        <dl class="mx-auto mt-10 grid max-w-3xl grid-cols-2 gap-8 sm:grid-cols-4">
            <div><dt class="text-2xl font-bold">99.9%</dt><dd class="mt-1 text-sm text-gray-400">Uptime</dd></div>
            <div><dt class="text-2xl font-bold">&lt;60ms</dt><dd class="mt-1 text-sm text-gray-400">Latency</dd></div>
            <div><dt class="text-2xl font-bold">8K+</dt><dd class="mt-1 text-sm text-gray-400">Customers</dd></div>
            <div><dt class="text-2xl font-bold">150+</dt><dd class="mt-1 text-sm text-gray-400">Countries</dd></div>
        </dl>
    </div>
</section>

{{-- Photo cards --}}
<section class="photo-card-section mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="max-w-2xl">
        <p class="section-eyebrow">Built for everywhere</p>
        <h2 class="mt-2 text-3xl font-bold text-ink-900">A clearer route to global reach</h2>
        <p class="mt-3 text-gray-600">Give every workflow the right local context, from market research to customer verification.</p>
    </div>
    <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
        <a href="{{ route('proxies') }}" class="photo-card photo-card-tall">
            <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=900&q=85" alt="Team collaborating around a laptop" loading="lazy">
            <span class="photo-card-overlay"></span>
            <span class="photo-card-copy"><span class="text-xs font-semibold uppercase tracking-widest text-cyan-200">Teams</span><strong>Research without borders</strong><span>Reliable access for fast-moving operations.</span></span>
        </a>
        <a href="{{ route('virtual-numbers') }}" class="photo-card">
            <img src="https://images.unsplash.com/photo-1526738549149-8e07eca6c147?auto=format&fit=crop&w=900&q=85" alt="Smartphone displaying a connected city map" loading="lazy">
            <span class="photo-card-overlay"></span>
            <span class="photo-card-copy"><span class="text-xs font-semibold uppercase tracking-widest text-violet-200">Presence</span><strong>Be local anywhere</strong><span>Numbers that make every conversation feel closer.</span></span>
        </a>
        <a href="{{ route('pricing') }}" class="photo-card">
            <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=85" alt="Bright modern office workspace" loading="lazy">
            <span class="photo-card-overlay"></span>
            <span class="photo-card-copy"><span class="text-xs font-semibold uppercase tracking-widest text-emerald-200">Momentum</span><strong>Scale with confidence</strong><span>Simple pricing and infrastructure that keeps pace.</span></span>
        </a>
    </div>
</section>

{{-- Overview cards --}}
<section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <div class="overview-card overview-card-proxy">
            <div class="overview-card-content">
                <div class="overview-card-icon">IP</div>
                <p class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-white/70">Global IP access</p>
                <h3 class="mt-2 text-xl font-bold">Anonymous Proxies</h3>
                <p class="mt-2 text-sm leading-6 text-white/80">Residential and mobile IPs sourced from 150+ countries.</p>
                <a href="{{ route('proxies') }}" class="overview-card-link mt-6">Explore proxies <span aria-hidden="true">→</span></a>
            </div>
        </div>
        <div class="overview-card overview-card-number">
            <div class="overview-card-content">
                <div class="overview-card-icon">#</div>
                <p class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-white/70">Local presence</p>
                <h3 class="mt-2 text-xl font-bold">Virtual Numbers</h3>
                <p class="mt-2 text-sm leading-6 text-white/80">Local numbers that give your business a real global presence.</p>
                <a href="{{ route('virtual-numbers') }}" class="overview-card-link mt-6">Explore numbers <span aria-hidden="true">→</span></a>
            </div>
        </div>
        <div class="overview-card overview-card-speed">
            <div class="overview-card-content">
                <div class="overview-card-icon">↗</div>
                <p class="mt-6 text-xs font-bold uppercase tracking-[0.18em] text-white/70">Built for momentum</p>
                <h3 class="mt-2 text-xl font-bold">High Speed</h3>
                <p class="mt-2 text-sm leading-6 text-white/80">Low-latency routing with no bandwidth restrictions.</p>
                <a href="{{ route('pricing') }}" class="overview-card-link mt-6">View plans <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </div>
</section>
@endsection
