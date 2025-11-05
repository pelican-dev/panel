# CODEX

## Project Snapshot
- **Name:** Pelican Panel (`pelican-dev/panel`)
- **Purpose:** Laravel-based management panel for orchestrating game servers (Minecraft, Spigot, BungeeCord, SRCDS) including provisioning, backups, scheduling, and remote control.
- **Backend Stack:** PHP 8.2+, Laravel 12, Laravel Sanctum, Socialite, Spatie packages (Permission, Query Builder, Data, Fractal, Health), Guzzle, AWS SDK, Livewire (via Filament), lcobucci/jwt.
- **Frontend Stack:** Vite, Tailwind CSS, Laravel Vite plugin, Alpine/Livewire-backed Filament UI, XTerm.js console components.
- **Supporting Tooling:** Pest for testing, PHPStan & Larastan for static analysis, Laravel Pint for formatting, Docker environment via `Dockerfile*` and `compose.yml`.

## High-Level Layout
- `app/` – Application code (domain models, services, controllers, jobs, policies, view components, Livewire/Filament resources).
- `bootstrap/` – Laravel bootstrap files (`app.php`, cache stubs).
- `config/` – Configuration for framework and packages (panel behavior, services, logging, health, permissions, etc.).
- `database/` – Migrations defining schema, factories for seeding models, and seeders.
- `lang/` – Localized strings for UI and panel messaging across many locales.
- `public/` – Web entry point (`index.php`) plus published assets (icons, compiled CSS/JS).
- `resources/` – Blade templates, Livewire/Filament views, Tailwind CSS, and Vite-managed JavaScript.
- `routes/` – Route definition files split by surface (API, remote, auth, docs).
- `storage/` – Runtime caches, logs, compiled views (ignored from VCS content-wise).
- `tests/` – Pest-based test suites (unit, feature, integration, Filament component tests).

## Backend Details (`app/`)
- `Checks/` – Custom health checks wired into `spatie/laravel-health`.
- `Console/` – Artisan command definitions (`Kernel.php`, domain-specific subcommands under `Commands/**`).
- `Contracts/` – Interfaces for repositories/services to formalize dependencies.
- `Eloquent/` – Query builder extensions (e.g., `BackupQueryBuilder`).
- `Enums/` – Backed enums modeling domain states and types.
- `Events/` – Domain events (auth, activity logging, server lifecycle) with paired listeners in `Listeners/`.
- `Exceptions/` – Custom exceptions for API responses and domain errors.
- `Extensions/` – Integration glue for third-party packages/framework extensions.
- `Facades/` – Facade classes for internal services.
- `Filament/` – Admin panel resources:
  - `Admin/`, `App/`, `Server/` – Filament pages, relation managers, tables/forms controlling panel UX.
  - `Components/`, `Pages/` – Shared UI components and route-bound Filament pages.
- `Helpers/` and `helpers.php` – Reusable helper functions.
- `Http/` – HTTP layer:
  - `Controllers/` – Web/API controllers grouped by surface (`Api/Application`, `Api/Client`, `Api/Remote`, `Auth`).
  - `Middleware/` – Request middleware (auth guards, throttling, panel-specific checks).
  - `Requests/` – Form request validators ensuring input sanitization.
  - `Resources/` – API Resource transformers for JSON responses.
- `Jobs/` – Queueable tasks (backups, remote sync, server actions).
- `Listeners/` – Event listeners handling notifications, auditing, provisioning.
- `Livewire/` – Livewire components for real-time UI segments (installer, server cards, alert banners).
- `Models/` – Eloquent models representing core entities (`Server`, `Node`, `Egg`, `User`, `Backup`, etc.). Includes nested `Filters/`, `Objects/`, `Traits/` for query utilities and attribute casting.
- `Notifications/` – Laravel notification classes for mail, webhook, and alerting channels.
- `Observers/` – Model observers hooking into lifecycle events.
- `Policies/` – Authorization logic consumed via `spatie/laravel-permission` and Laravel gates.
- `Providers/` – Service providers registering bindings, events, Filament configuration, and extension points.
- `Repositories/Daemon/` – Abstractions for communicating with the remote daemon (panel agent API).
- `Rules/` – Custom validation rules.
- `Services/` – Domain services organized by bounded context (`Servers`, `Nodes`, `Allocations`, `Files`, `Backups`, `Schedules`, etc.) encapsulating business workflows and daemon API orchestration.
- `Traits/` – Shared behaviour traits mixed into models/services.
- `Transformers/Api/` – Fractal transformers for backward-compatible API payloads.

## Routing & Entry Points
- `routes/base.php` – Web routes for the panel UI (Filament, dashboards, documentation redirects).
- `routes/auth.php` – Authentication routes (login/logout, OAuth via Socialite providers).
- `routes/api-application.php` – Administrative API endpoints for application-level integrations.
- `routes/api-client.php` – Client-facing API for server owners/subusers.
- `routes/api-remote.php` – Remote daemon callback endpoints.
- `routes/docs.php` – API documentation endpoints generated via `dedoc/scramble`.
- `app/Providers/RouteServiceProvider.php` ties files into route groups, middleware, and rate limiting.

## Presentation Layer
- `resources/views/filament` – Blade overrides for Filament layout/pages.
- `resources/views/livewire` – Blade views paired with Livewire components.
- `resources/views/docs` – Documentation templates served from `routes/docs.php`.
- `resources/views/components` – Blade component templates.
- `resources/css` & `resources/js` – Tailwind CSS entrypoints (`app.css`, `console.css`) and JS bootstraps for Vite (`app.js`, `console.js`).
- `public/css`, `public/js` – Built asset output; served via Vite or after `npm run build`.

## Data & Persistence
- `database/migrations` – Schema changes for all core entities (users, servers, nodes, schedules, permissions, activity logs, etc.).
- `database/Factories` – Model factories for tests and seeding.
- `database/Seeders` – Seed classes for baseline roles/permissions, initial configuration.
- `config/database.php`, `config/cache.php`, `config/session.php` – Database/cache configuration (supports MySQL, Redis, etc.).

## Security & Auth
- Laravel Sanctum for API token authentication (`config/sanctum.php`).
- `config/permission.php` with Spatie roles/permissions integration.
- Social login providers configured via `config/services.php` (Discord, Steam, Authentik).
- Policies and middleware enforce per-resource access control.

## Integrations & Services
- Remote daemon communication via `Repositories/Daemon` and services under `app/Services/Servers`, `.../Nodes`.
- Backup management orchestrated through `app/Services/Backups` with storage abstraction (S3 via `league/flysystem-aws-s3` supported).
- Notifications (mail, webhook) configured in `config/mail.php` and `app/Notifications`.
- Health monitoring through `spatie/laravel-health` configured in `config/health.php` and `app/Checks`.

## Frontend Build System
- Vite configuration in `vite.config.js` managing Laravel + Tailwind pipeline.
- Tailwind setup uses plugins (`@tailwindcss/forms`, `@tailwindcss/typography`).
- XTerm-based console UI powered by dependencies in `package.json`.

## Testing & Quality
- Pest orchestrates unit, feature, integration, and Filament tests (`tests/Unit`, `tests/Feature`, etc.).
- Shared testing utilities live under `tests/Assertions` and `tests/Traits`.
- Static analysis via PHPStan/Larastan (`phpstan.neon`).
- Code style enforced with Laravel Pint (`pint.json`).

## DevOps & Environment
- Docker setup (`Dockerfile`, `Dockerfile.base`, `Dockerfile.dev`, `compose.yml`) for local/CI environments.
- `artisan` – CLI for migrations, queue workers, health checks, etc.
- `config/panel.php`, `config/activity.php`, `config/backups.php` – Core panel tuning knobs.
- Localization handled via `lang/**` with Crowdin configuration in `crowdin.yml`.

## Getting Oriented
1. Start with `config/panel.php` and `app/Providers/AppServiceProvider.php` to grasp custom bindings and defaults.
2. Follow request flow: `routes/*.php` ➜ `app/Http/Controllers/**` ➜ `app/Services/**` ➜ `app/Repositories/Daemon/**` for remote calls.
3. UI customization lives in `app/Filament/**` and `resources/views/filament/**`.
4. Domain rules and data transformations are centered in `app/Models/**`, `app/Transformers/Api/**`, and `app/Policies/**`.
