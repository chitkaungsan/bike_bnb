# Bike BnB — Engineering Guidelines

This document captures how this repository is organized and the conventions/patterns used across the backend (Laravel) and frontend (Vue 3). It is meant to be a practical reference for contributors.

## Tech Stack Overview

* Backend: Laravel (Passport for API auth, Socialite for Google OAuth)
* Frontend: Vue 3, Vue Router 4, Vuex 4, Vite
* UI/Styles: Tailwind CSS v4, PrimeVue, Bootstrap 5, Font Awesome, NProgress
* Maps/Media: Leaflet, Mapbox GL, FilePond (+ image preview/validate plugins)
* Realtime: Laravel Echo + Pusher
* I18n: `vue-i18n`
* HTTP: Axios with request interceptor

Environment variables expected (non-exhaustive):

* API/auth: `APP_URL`, `APP_KEY`, `AUTH_GUARD`, `AUTH_PASSWORD_BROKER`
* Passport: uses `api` guard with `driver => passport`
* Socialite: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
* AWS S3: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, bucket config
* Frontend: `VITE_API_URL` for Axios base URL
* Broadcasting/Realtime: Pusher keys (see `config/broadcasting.php` if used)

## Repository Layout

* app/
  * Http/Controllers/Api: Feature-specific REST controllers (e.g., AuthController, BikeController)
  * Models: Eloquent models (e.g., User)
* config/
  * auth.php: `api` guard uses `passport`
  * services.php: Socialite (google), AWS SES, Postmark, Slack
* database/: migrations, seeders, factories (standard Laravel layout)
* public/: Laravel public assets; SPA entry served via `resources/views/app.blade.php`
* resources/
  * views/app.blade.php: mounts SPA `#app` and loads Vite assets (scss + js)
  * js/
    * app.js: Vue app bootstrap; registers router, store, i18n, UI libs
    * router/: route modules and guards; uses NProgress
    * store/: Vuex store with namespaced modules (auth, bikes, booking, store, users, cities, theme, homeFilter)
    * service/axios.js: Axios instance w/ Authorization header from localStorage
    * locales/: translation message files (`en.js`, `th.js`)
    * i18n.js: sets up `vue-i18n` with persisted/browser locale selection
    * components/, Pages/: SFCs following feature/page organization
* routes/
  * web.php: SPA catch-all → `view('app')`
  * api.php: top-level API routes; includes feature route files
  * api_*.php: modular route files (bikes, stores, booking, etc.)
* tests/: PHPUnit structure (Feature/Unit) — add tests alongside features
* vite.config.js: Vite + Laravel plugin configuration
* package.json: `dev`, `build` scripts; Vue plugins
* composer.json: Laravel and Passport/Socialite, etc.

## Backend Conventions

### Routing

* All web (non-API) traffic is routed to a single SPA view `resources/views/app.blade.php`.
* REST API endpoints defined in `routes/api.php` and split into `routes/api_*.php` for each domain (e.g., `api_bike.php`).
* Example (from `api_bike.php`):
  * POST `bike/store` → `BikeController@bikeStore`
  * GET `bike/brands`, `bike/categories`
  * GET `get/bikes/{user_id}`, `get/bike/all`, `bikes/{id}`

Guideline: Prefer consistent resourceful naming, e.g., `bikes.index`, `bikes.show`, `bikes.store`. If legacy routes exist, add new RESTful endpoints while maintaining BC.

### Controllers

* Controllers live under `App\Http\Controllers\Api` for API endpoints.
* Keep controllers thin: delegate validation to Form Requests and data shaping to API Resources (if/when present). If not present in current code, introduce gradually when touching a controller.

### Models

* Eloquent models under `App\Models`.
* `User` uses `Laravel\Passport\HasApiTokens`, `HasFactory`, `Notifiable`.
* Use `$fillable` for mass-assignment; keep sensitive fields under `$hidden`.
* Prefer attribute casting via `casts()` (e.g., `email_verified_at`, hashed `password`).

### Authentication & Authorization

* API auth: Passport personal access tokens; `api` guard `driver => passport`.
* Frontend stores token in `localStorage` under `token`; Axios attaches `Authorization: Bearer <token>` via interceptor.
* Auth routes include: `/login`, `/register`, `/user` (current user), `/user/set/role`, and Google OAuth callback via Socialite.
* Add authorization via policies/gates for resource ownership. Place policies in `app/Policies` and register in `AuthServiceProvider`.

### Storage & Media

* S3 configured via `config/filesystems.php` and `config/services.php` (AWS). Upload endpoints (e.g., avatar) should validate and store to S3 using signed URLs or server-side upload. Keep bucket and ACL settings in env.

### Realtime & Notifications

* Realtime via Laravel Echo + Pusher (see frontend dependencies). Events should implement `ShouldBroadcast` where needed. Configure channels in `routes/channels.php`.
* Use Laravel Notifications and Mail where appropriate; third-party mail services configured in `config/services.php`.

### Errors & Responses

* Return JSON responses with consistent shape:
  * `{ data: ..., message: string, errors?: object, meta?: object }`
* On validation errors, rely on Laravel’s standard 422 JSON. Consider API Resources for consistent `data` envelopes.

## Frontend Conventions

### App Bootstrap

* `resources/js/app.js` creates the Vue app and registers:
  * Router (`resources/js/router`)
  * Vuex store (`resources/js/store`)
  * i18n (`resources/js/i18n.js`)
  * UI libraries (PrimeVue, Bootstrap/Icons, Font Awesome) as needed
* SPA mounted on `#app` from `resources/views/app.blade.php` via Vite.

### Routing

* Define routes in `resources/js/router/index.js` and split per domain (`owner.js`, `admin_.js`, `renter.js`, `booking.js`).
* Use `createWebHistory()`; enable `scrollBehavior` to reset scroll to top.
* Global guards:
  * NProgress on `beforeEach`/`afterEach`.
  * Auth guard: when `to.meta.requiresAuth` and no user in store, dispatch `auth/fetchUser` (calls `/user`), or redirect to `Home` on failure.
* Public OAuth callback path `/auth/google/callback` bypasses guard.

### State Management (Vuex)

* Store is namespaced per module:
  * `auth`, `users`, `store`, `bikes`, `booking`, `cities`, `theme`, `homeFilter`.
* Auth module pattern (see `store/modules/Auth.js`):
  * State: `user`, `token` (persisted in `localStorage`)
  * Getters: `isAuthenticated`, `user`, `userRole`
  * Mutations: `SET_USER`, `SET_TOKEN`, `LOGOUT`
  * Actions: `login`, `register` (save token, then `fetchUser`), `fetchUser`, `setUserRole`, `logout`
* Guideline: Keep API calls inside actions; mutations synchronous; getters for derived state.

### HTTP Layer (Axios)

* Single axios instance in `resources/js/service/axios.js` with:
  * `baseURL: import.meta.env.VITE_API_URL`
  * Request interceptor to attach `Authorization` header from `localStorage` token
* Guideline: Avoid duplicating axios instances; centralize interceptors and error handling.

### Internationalization (i18n)

* `resources/js/i18n.js` sets locale from `localStorage` or browser language (supports `en`, `th`).
* Use `useI18n()` in components; maintain messages under `resources/js/locales/`.
* Provide a language switcher component for runtime locale changes.

### UI/Styling

* Tailwind CSS v4 via Vite plugin; global styles in `resources/css/app.scss`.
* PrimeVue components where appropriate; Bootstrap utilities are also installed.
* NProgress is used for route transition feedback.

### Components & Pages

* Pages under `resources/js/Pages/...` for route-level views (e.g., `Auth/Login.vue`, `Bike/Bikes.vue`).
* Reusable building blocks under `resources/js/components/...` (e.g., Home, Bikes, Layout, StoreDetail).
* Prefer Composition API with `<script setup>` when adding new components; follow existing style in current files for consistency.

## Testing

* PHP: PHPUnit with standard Laravel structure (`tests/Feature`, `tests/Unit`). Use model factories in `database/factories` and seeders as needed.
* JS: No formal Jest/Vitest setup currently. Prefer integration testing via Laravel feature tests and browser testing if needed. If adding frontend unit tests, introduce Vitest in a separate PR with minimal config.

## Development Workflow

* Requirements: PHP >= version compatible with current Laravel, Composer, Node.js (matching Vite/Vue), npm.
* Install:
  * `composer install`
  * `cp .env.example .env` and configure env vars (DB, Passport, AWS, Google OAuth, VITE_API_URL)
  * `php artisan key:generate`
  * `php artisan migrate --seed` (if seeds exist)
  * `php artisan passport:install` (generate keys and clients)
  * `npm install`
* Run:
  * Backend: `php artisan serve` (or Valet/Homestead)
  * Frontend: `npm run dev` (Vite dev server)
  * Visit the app URL served by Laravel; Vite will handle assets via the Laravel Vite plugin
* Build:
  * `npm run build` to produce production assets

## Code Style & Linting

* PHP: Follow Laravel conventions. Consider adding Laravel Pint for automated formatting.
* JS/TS: No ESLint/Prettier configured in repo; keep consistent with existing code style. If adding linters, configure minimally and apply incrementally.

## Security & Privacy

* Do not log or expose access tokens. Tokens are stored in `localStorage`. Consider rotating to HttpOnly cookies if CSRF strategy is defined and SPA constraints allow.
* Validate and sanitize all upload inputs; enforce file type/size limits server-side. Prefer presigned URLs for large uploads.
* For OAuth callbacks, ensure redirect URIs are correctly configured and validated.

## Adding New Features — Checklist

1. Define routes in a new or existing `routes/api_<feature>.php` file; keep naming RESTful where possible.
2. Add a controller under `App\Http\Controllers\Api`.
3. Add Request classes for validation; add Policies for authorization if resource-bound.
4. Create/extend Eloquent models and migrations.
5. Expose data via API Resources if shaping complex responses.
6. Frontend: add Vuex module or extend existing one; add Pages and Components; add route(s) with `meta.requiresAuth` when needed.
7. Wire API calls through the centralized axios instance.
8. Add i18n strings to `locales`.
9. Add tests (Feature at minimum) and run the suite.

## Troubleshooting Tips

* 401 errors on protected routes usually mean missing/expired token: check `localStorage.token` and Axios interceptor.
* CORS/API base URL issues: verify `VITE_API_URL` matches Laravel app URL (and includes `/api` if used).
* OAuth callback mismatch: ensure `GOOGLE_REDIRECT_URI` equals the frontend callback path and is registered in Google Cloud.
* S3 upload failures: verify AWS credentials and bucket policy; check region.

---

This document will evolve with the codebase. When you introduce a new pattern or tool, please add it here along with examples and rationale.
