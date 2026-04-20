# AGENTS.md

## Use These Commands
- Use `npm`, not `pnpm`. CI installs with `npm i`, and the repo is locked by `package-lock.json` even though `pnpm-workspace.yaml` exists.
- First-time setup: `composer setup`.
- Local dev: `composer dev`.
  Starts `php artisan serve`, `php artisan queue:listen --tries=1`, and `npm run dev` together.
- Full verification: `composer ci:check`.
  Runs `npm run lint:check`, `npm run format:check`, `npm run types:check`, then tests.
- PHP formatting: `composer lint` to fix, `composer lint:check` to verify.
- Frontend formatting/linting/typecheck: `npm run format`, `npm run format:check`, `npm run lint`, `npm run lint:check`, `npm run types:check`.

## Testing
- Main test runner is Pest on top of Laravel: CI uses `./vendor/bin/pest`.
- Focus a test with `./vendor/bin/pest --filter <name>` or `php artisan test --filter=<name>`.
- `composer test` is not just tests: it clears config and runs `composer lint:check` before `php artisan test`.
- Test DB is in-memory SQLite from `phpunit.xml` (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).
- `tests/Pest.php` only extends `tests/Feature`; `RefreshDatabase` is commented out globally. Add DB reset traits explicitly in tests that need them.

## App Wiring
- This is a Laravel 13 + Inertia Vue 3 app, not a separate API/frontend split.
- Main backend wiring is in `bootstrap/app.php`; it registers only `routes/web.php`, `routes/console.php`, and health check `/up`.
  There is no `routes/api.php` entrypoint configured.
- Frontend entrypoint is `resources/js/app.ts` with Vite inputs `resources/css/app.css` and `resources/js/app.ts` from `vite.config.ts`.
- Inertia page names control layouts in `resources/js/app.ts`:
  `Welcome` gets no layout, `auth/*` uses `AuthLayout`, `settings/*` uses `[AppLayout, SettingsLayout]`, everything else uses `AppLayout`.
- Shared Inertia props come from `App\Http\Middleware\HandleInertiaRequests`: app name, `auth.user`, and `sidebarOpen` from the `sidebar_state` cookie.
- Theme state is cookie-driven: `HandleAppearance` shares `appearance`, and `bootstrap/app.php` exempts `appearance` and `sidebar_state` from cookie encryption.
- Fortify auth screens are rendered through `App\Providers\FortifyServiceProvider` into Inertia pages under `resources/js/pages/auth/*`, not Blade views.

## Current Product Shape
- `routes/web.php` defines `users` and `roles` resource routes outside the authenticated dashboard group. Do not assume those resources are auth-protected unless you verify and change the routes.
- Users CRUD is implemented end-to-end (`app/Http/Controllers/UserController.php` + `resources/js/pages/Users/*`).
- Roles are not implemented yet: `app/Http/Controllers/RoleController.php` is still stubbed and there are no `resources/js/pages/Roles/*` pages.
- Permission seeding currently creates only `users.*` and `roles.*` permissions with actions `create`, `view`, `edit`, `delete` in `database/seeders/PermissionSeeder.php`.

## Frontend / Tooling Quirks
- TypeScript path alias is only `@/* -> resources/js/*` from `tsconfig.json`.
- Prettier only targets `resources/`, uses 4 spaces, single quotes, semicolons, and the Tailwind plugin with `resources/css/app.css` as the stylesheet.
- ESLint ignores `resources/js/actions/**`, `resources/js/routes/**`, `resources/js/wayfinder/**`, and `resources/js/components/ui/*` plus build/vendor output. Changes there are not covered by normal frontend lint runs.
