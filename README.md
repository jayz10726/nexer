# NEXER PROX

A Laravel 11 + Tailwind CSS marketing site for a proxies & virtual numbers business — Home, Proxies, Virtual Numbers, and Pricing pages, plus full authentication (register/login/logout) and a database-driven pricing table.

## What's included

- Laravel 11 application structure (routes, controllers, models, migrations, config)
- Tailwind CSS via Vite, with a custom brand color palette (`tailwind.config.js`)
- `User` auth (Breeze-style, hand-rolled): register, login, logout, **plus "Continue with Google" via Laravel Socialite**
- `Plan` model + migration + seeder powering the `/pricing` page from the database
- A logged-in user **dashboard** with: Overview, My Proxies, Virtual Numbers, Transactions, Wallet, Settings
- A **super admin panel** (`/admin`) for managing every user, their proxies, numbers, transactions, and wallet balances
- Responsive nav, footer, and 4 marketing pages: Home, Proxies, Virtual Numbers, Pricing

## Setting up "Continue with Google"

1. In [Google Cloud Console](https://console.cloud.google.com/apis/credentials), create an **OAuth 2.0 Client ID** of type **Web application**.
2. Add the origin users visit to **Authorized JavaScript origins** (for production, `https://nexerprox.co.ke`).
3. Add the exact callback URL to **Authorized redirect URIs**. For production, use `https://nexerprox.co.ke/auth/google/callback`; for local Apache on port 80, use `http://localhost/auth/google/callback`. A local `php artisan serve` on port 8000 instead needs `http://localhost:8000/auth/google/callback`.
4. On the server running the app, set these values in its `.env`:
   ```
   APP_URL=https://nexerprox.co.ke
   GOOGLE_CLIENT_ID=your-client-id
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT_URI=https://nexerprox.co.ke/auth/google/callback
   ```
   Keep the client secret only in the server environment; do not commit it.
5. Deploy the application and its migrations, then run `php artisan migrate --force` and rebuild Laravel's cached configuration with `php artisan config:clear && php artisan config:cache`. Ensure Apache's document root is the Laravel `public` directory and rewrite rules are enabled.

The "Continue with Google" button on `/login` and `/register` redirects to Google when the server has both OAuth credentials configured.

Google callback handling validates OAuth state and only accepts verified Google email addresses. If an account already exists for that email, sign in with that account first and connect Google from **Dashboard → Settings**; accounts are not automatically linked based on email alone.

New users signing up via Google (or email) get seeded with a small demo wallet bonus, one sample proxy, and one sample virtual number so the dashboard isn't empty on first login — see `app/Support/DemoDataSeeder.php`. Remove or replace this once you wire up real provisioning.

## The dashboard

Once logged in, users land on `/dashboard` with a sidebar:

- **Overview** — quick stats (proxy count, number count, wallet balance) and recent transactions
- **My Proxies** (`/dashboard/proxies`) — the user's assigned proxies (`App\Models\Proxy`)
- **Virtual Numbers** (`/dashboard/virtual-numbers`) — the user's numbers (`App\Models\VirtualNumber`)
- **Transactions** (`/dashboard/transactions`) — full wallet ledger (`App\Models\Transaction`)
- **Wallet** (`/dashboard/wallet`) — balance + Paystack-powered top-ups
- **Settings** (`/dashboard/settings`) — update name, email, password

## Setting up Paystack wallet top-ups

1. Create or sign in to a [Paystack account](https://dashboard.paystack.com/) and copy your test keys from **Settings → API Keys & Webhooks**.
2. Add them to `.env`:
   ```
   PAYSTACK_PUBLIC_KEY=pk_test_your_key
   PAYSTACK_SECRET_KEY=sk_test_your_key
   PAYSTACK_CURRENCY=USD
   ```
3. Set `APP_URL` to the URL users can return to after checkout. The callback is `/dashboard/wallet/paystack/callback`.

On the wallet page, users choose a Paystack channel: Card, Bank account, Apple Pay, USSD, QR code, Mobile money, or Bank transfer. The wallet then redirects them to Paystack's secure hosted checkout, where Paystack collects the card or bank details and PIN when required. Sensitive payment details never pass through this app. Availability depends on the currency, country, device, and payment methods enabled for your Paystack account. Funds are credited only after the server verifies a successful transaction with Paystack.

Each top-up now has a server-stored intent bound to its initiating user, wallet amount, gateway amount, currency, and unique reference. The verified callback credits it atomically at most once. During deployment, let any payments already in progress finish before switching to this version, or reconcile them manually with Paystack before crediting wallets.

## The super admin panel

The initial privileged account is created during the first database seed. Set these values privately in `.env` first:

```
SUPER_ADMIN_NAME="Super Admin"
SUPER_ADMIN_EMAIL=
SUPER_ADMIN_PASSWORD=
```

Use your deployment account's email and a unique password with at least 16 characters; generate and store it in a password manager. The example intentionally contains no working credentials. The seeder refuses missing or known placeholder passwords, creates the account only if the email is unused, and never resets an existing account's password or role. If the email already belongs to a regular account, the seeder stops rather than elevating it. After setup, sign in at `/login`; authorized accounts see the admin link in their dashboard.

The admin panel at `/admin` lets you:

- **Users** (`/admin/users`) — search/filter every account, open one to change its role (user / admin / super_admin — role changes are super-admin-only), suspend or reactivate the account, manually credit/debit their wallet, or delete the account entirely (also super-admin-only)
- **Proxies** (`/admin/proxies`) — see every proxy across all users, assign a new one to any user, edit or remove existing ones
- **Virtual Numbers** (`/admin/virtual-numbers`) — same, for virtual numbers
- **Transactions** (`/admin/transactions`) — the full platform-wide wallet ledger, filterable by type

Access is enforced by two middleware: `admin` (role `admin` or `super_admin`) guards the whole `/admin` area, and `super_admin` additionally guards role changes and account deletion. A suspended account is blocked from logging in (both password and Google login) until an admin reactivates it.

Super admins can also open `/admin/login-activity` to review successful password/Google sign-ins and currently active sessions. Sign-in history starts when this feature is deployed; it does not backfill older logins. Active session visibility requires `SESSION_DRIVER=database`.

Password sign-in is limited to five attempts per minute per email/IP pair and 120 attempts per minute per IP. For multiple application servers, use a shared production cache backend so throttling is consistent across instances.

To promote an existing regular user to `admin` later, either use the super admin panel (`/admin/users/{id}` → change Role to Admin) or run:
```bash
php artisan tinker
>>> \App\Models\User::where('email', 'someone@example.com')->update(['role' => 'admin']);
```



## Important: install dependencies first

This project's `vendor/` and `node_modules/` folders are **not included** in the zip (they're large, environment-specific, and normally never committed to source control). You need to install them yourself:

### 1. Requirements
- PHP 8.2+
- Composer
- Node.js 18+ and npm

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Set up your environment
```bash
cp .env.example .env
php artisan key:generate
```

The project defaults to **SQLite** (already configured, `database/database.sqlite` is included as an empty file). If you'd rather use MySQL/Postgres, edit the `DB_*` values in `.env`.

### 4. Configure the initial privileged account, then migrate and seed
Set a unique `SUPER_ADMIN_EMAIL` and strong `SUPER_ADMIN_PASSWORD` in `.env` before running the seeder, as described above.

```bash
php artisan migrate --seed
```

### 5. Install JS dependencies and build Tailwind
```bash
npm install
npm run build
```
(or `npm run dev` while actively developing, for hot reload)

### 6. Serve the app
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000`.

### Production deployment checklist

Do not deploy the local `.env.example` values as-is. On the production host configure `APP_ENV=production`, `APP_DEBUG=false`, an HTTPS `APP_URL`, a unique `APP_KEY`, production database credentials, and `SESSION_SECURE_COOKIE=true`. Use a production database and a shared session/cache backend for multiple app instances. Keep OAuth and Paystack secrets only in the host's secret environment, set Google's exact HTTPS callback URL, and use Paystack live keys only after completing a live-mode verification.

Deploy code and built assets, then run:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Confirm HTTPS is enforced at the proxy/web server, the web root points only to `public/`, and backups, monitoring, queue workers, and scheduled tasks are configured before accepting customers.

## Project structure notes

- `app/Http/Controllers/PageController.php` — serves the 4 marketing pages
- `app/Http/Controllers/Auth/` — registration & session (login/logout) controllers
- `app/Models/Plan.php` + `database/seeders/PlanSeeder.php` — edit the seeder to change pricing tiers, then re-run `php artisan db:seed`
- `resources/views/` — all Blade templates (`layouts/app.blade.php` is the shared shell)
- `resources/css/app.css` — Tailwind entry point + a few reusable component classes (`.btn-primary`, `.card`, etc.)
- `tailwind.config.js` — brand colors live under the `brand` and `ink` keys; change these to reskin the site

## Buying proxies and virtual numbers

Both **My Proxies** and **Virtual Numbers** now have a full purchase flow, not just admin-assigned resources:

**Proxies** (`/dashboard/proxies/buy`): Continent → Country → Proxy Type (Residential, Datacenter, Mobile, ISP) → Price → Purchase. All 6 populated continents (Africa, Asia, Europe, North America, South America, Oceania — Antarctica excluded) and their countries live in `config/geo.php`.

**Virtual Numbers** (`/dashboard/virtual-numbers/buy`): Continent → Country → Service → Price → Purchase. The service list (`app/Support/ServiceCatalog.php`) is grouped into:
- **AI & Data Work**: AI Training Services, Data Annotation, Outlier AI, Handshake, Mercor, Scale AI, Appen, Lionbridge AI, TELUS International, Clickworker, Remotask
- **Messaging & Social**: WhatsApp, Telegram, Signal

Each service card shows a simple generic icon (`resources/views/dashboard/numbers/_icon.blade.php`) tinted with a brand-adjacent accent color — these are original shapes, not the platforms' actual logos, to stay clear of trademark issues while still looking distinct per service.

**Pricing**: `App\Support\PricingCatalog::price()` derives a deterministic $6–$10 price from a hash of continent+country+type (or +service), so the same combination always quotes the same price to every user without needing a giant pre-priced table. A small JSON endpoint (`/dashboard/proxies/buy/price`, `/dashboard/virtual-numbers/buy/price`) returns that price so what's shown always matches what gets charged. Swap `PricingCatalog::price()` for real supplier-cost-based pricing whenever you have it.

**Purchasing** deducts the quoted price from the user's `wallet_balance`, blocks the purchase with a clear error if the balance is too low, and logs a `Transaction` row plus the new `Proxy`/`VirtualNumber` record. The generated IP/port/credentials and phone numbers are **synthetic placeholders** for demo purposes — wire up your real proxy/SMS supplier's API in `ProxyController::store()` / `VirtualNumberController::store()` before going live.

This flow uses [Alpine.js](https://alpinejs.dev) (added via npm, loaded through Vite) for the interactive continent → country → type/service steps — no extra build step beyond the usual `npm install && npm run build`.

## Customizing

## UX improvements for buying

A few things were added specifically to make purchasing painless:

- **Progressive steps, not a wall of options**: the buy flows (`/dashboard/proxies/buy`, `/dashboard/virtual-numbers/buy`) now show one step at a time with a progress bar, instead of revealing every country grid at once. Completed steps collapse into small "chip" pills (e.g. 🌍 Africa · Change) that you can click to jump back and change that choice without restarting.
- **Country search**: typing in the search box live-filters the country list — much faster than scrolling through 50+ buttons per continent.
- **Actionable "insufficient balance" errors**: instead of just red text, a low-balance error shows a **Top Up Wallet** button right there.
- **Quick top-up presets**: the wallet page has $10/$25/$50/$100 one-click buttons alongside the custom amount field.
- **"Buy Now" in the main nav**: logged-in users get a dropdown (desktop) / inline links (mobile) straight to the two buy flows, without needing to open the dashboard first.
- **No dead ends**: the dashboard overview's stat cards say "Buy your first one →" instead of "View all →" when a user has nothing yet, and a first-time banner nudges brand-new accounts toward their first purchase.
- **Clearer marketing pages**: the Home, Proxies, and Virtual Numbers pages send logged-in visitors straight into the relevant buy flow (not just to a generic pricing page), and the Pricing page has a callout clarifying that one-off purchases don't require picking a plan at all.



## Brand palette

The whole app is themed around one palette, defined in `tailwind.config.js`:

| Role | Color | Hex |
|---|---|---|
| Primary CTA / links (`brand-500`) | Sky blue | `#00BFFF` |
| Bright accent (`brand-400`) | Cyan | `#00FFFF` |
| Dark backgrounds (`brand-800`/`ink-800`) | Deep navy | `#003262` |
| Gradient partner (`violet-400`) | Blue violet | `#8A2BE2` |
| Gradient partner (`violet-500`) | Purple | `#6F2DA8` |
| Success / positive amounts (`forest-400`) | Bright green | `#03C03C` |
| Deep green accent (`forest-700`) | Forest green | `#00401A` |

Primary buttons, the sidebar (user dashboard + admin), the logo mark, and the homepage hero all use a cyan → sky-blue → violet gradient (see `.btn-primary`, `.brand-mark`, `.gradient-text` in `resources/css/app.css`). Status badges and positive wallet amounts use the green (`forest-*`) scale instead of Tailwind's stock green, so everything reads as one consistent brand rather than default framework colors. To retheme, edit the hex values in `tailwind.config.js` — every view already references the semantic class names (`brand-600`, `violet-500`, `forest-400`, etc.) rather than hardcoded colors, so a palette swap there flows through the whole app.



- **Branding**: update the logo mark and site name in `resources/views/partials/nav.blade.php` and `footer.blade.php`, and the colors in `tailwind.config.js`.
- **Copy**: all page text lives directly in the Blade files under `resources/views/`.
- **Pricing**: edit `database/seeders/PlanSeeder.php` and re-seed, or manage the `plans` table directly.
