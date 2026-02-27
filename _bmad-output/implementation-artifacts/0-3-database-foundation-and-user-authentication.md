# Story 0.3: Database Foundation and User Authentication

Status: ready-for-dev

<!-- Note: Validation is optional. Run validate-create-story for quality check before dev-story. -->

## Story

As an **OPS Staff member**,
I want to log in to the Back-Office using my Telegram account (passwordless),
So that I can access the system without remembering a password.

As a **System Admin**,
I want to log in via email/password with 2FA,
So that the highest-privilege account is secured independently of Telegram.

## Acceptance Criteria

1. **Given** the database is configured with `utf8mb4` charset and `utf8mb4_unicode_ci` collation
   **When** I run `php artisan migrate`
   **Then** the `users` and `system_configs` tables are created with correct column types (NVARCHAR for name fields per D27)
   **And** `UserRole` enum defines all 7 roles: SADM, ADM, OPSM, OPS, BRK, ACCT, VIEW

2. **Given** a user with Telegram ID exists in the `users` table
   **When** they click the Telegram Login Widget on the login page
   **Then** the system verifies their Telegram identity and creates a session
   **And** they are redirected to the dashboard

3. **Given** a System Admin (SADM) user
   **When** they enter email/password + valid TOTP code
   **Then** they are authenticated via the `web` guard with 2FA verification

4. **Given** a user is not authenticated
   **When** they access any protected route (e.g., `/dashboard`)
   **Then** they are redirected to the login page

5. **Given** a successful login via either auth method
   **When** the session is created
   **Then** the login attempt is logged to the `audit` channel with `auth_guard`, `login_method`, `telegram_user_id` context (D24)

## Tasks / Subtasks

- [ ] **Task 1: Configure Database for UTF-8 / Khmer Support** (AC: #1)
  - [ ] Update `config/database.php` to set `'charset' => 'utf8mb4'` and `'collation' => 'utf8mb4_unicode_ci'`
  - [ ] Verify MySQL connection uses utf8mb4 charset

- [ ] **Task 2: Create `users` Table Migration** (AC: #1)
  - [ ] Run `php artisan make:migration create_users_table --no-interaction`
  - [ ] Replace the default Laravel `create_users_table` migration with custom schema per architecture.md
  - [ ] Columns: `id` (BIGINT PK AI), `uuid` (CHAR(36) UNIQUE NOT NULL), `name` (NVARCHAR(255) NOT NULL), `email` (VARCHAR(255) NULLABLE UNIQUE), `password` (VARCHAR(255) NULLABLE), `telegram_id` (VARCHAR(64) NULLABLE UNIQUE), `telegram_username` (VARCHAR(255) NULLABLE), `role` (ENUM NOT NULL), `is_active` (BOOLEAN DEFAULT true), `two_factor_secret` (TEXT NULLABLE), `two_factor_confirmed_at` (TIMESTAMP NULLABLE), `last_login_at` (TIMESTAMP NULLABLE), `remember_token` (VARCHAR(100)), timestamps, `deleted_at` (soft deletes)
  - [ ] Add indexes: `idx_users_telegram` on `telegram_id`, `idx_users_role` on `role`

- [ ] **Task 3: Create `system_configs` Table Migration** (AC: #1)
  - [ ] Run `php artisan make:migration create_system_configs_table --no-interaction`
  - [ ] Columns: `id` (BIGINT PK AI), `key` (VARCHAR(128) UNIQUE NOT NULL), `value` (TEXT NOT NULL), `group` (VARCHAR(64) NULLABLE), `description` (TEXT NULLABLE), timestamps

- [ ] **Task 4: Create `sessions` Table Migration** (AC: #2, #3)
  - [ ] Run `php artisan make:migration create_sessions_table --no-interaction` (or use `php artisan session:table`)
  - [ ] Switch `SESSION_DRIVER` from `file` to `database` in `.env`
  - [ ] Session table needed for both auth guards

- [ ] **Task 5: Create `UserRole` Enum** (AC: #1)
  - [ ] Create `app/Enums/UserRole.php` with cases: `SystemAdmin` ('SADM'), `Admin` ('ADM'), `OpsManager` ('OPSM'), `OpsStaff` ('OPS'), `Broker` ('BRK'), `Accounting` ('ACCT'), `Viewer` ('VIEW')
  - [ ] PascalCase enum case names per PHP conventions in GEMINI.md

- [ ] **Task 6: Create/Update `User` Model** (AC: #1, #2, #3)
  - [ ] Update existing `app/Models/User.php` model
  - [ ] Add `HasUuid` trait (create trait first — see Task 7)
  - [ ] Add `SoftDeletes` trait
  - [ ] Add `role` cast to `UserRole` enum in `casts()` method (not `$casts` property — per Laravel 12 conventions)
  - [ ] Add `fillable`: `name`, `email`, `password`, `telegram_id`, `telegram_username`, `role`, `is_active`, `two_factor_secret`, `two_factor_confirmed_at`, `last_login_at`
  - [ ] Add `hidden`: `password`, `remember_token`, `two_factor_secret`
  - [ ] Mark `email` and `password` as nullable in the model (only SADM uses them)

- [ ] **Task 7: Create `HasUuid` Trait** (AC: #1)
  - [ ] Create `app/Traits/HasUuid.php`
  - [ ] Auto-generate UUID on model `creating` event using `Str::uuid()`
  - [ ] Set `uuid` to non-incrementing, cast as string

- [ ] **Task 8: Create `SystemConfig` Model** (AC: #1)
  - [ ] Run `php artisan make:model SystemConfig --no-interaction`
  - [ ] Define `fillable`: `key`, `value`, `group`, `description`
  - [ ] No soft deletes (config entries are immutable — updated, not deleted)

- [ ] **Task 9: Create `UserFactory`** (AC: #1)
  - [ ] Run `php artisan make:factory UserFactory --model=User --no-interaction`
  - [ ] Define default state with faker-generated data
  - [ ] Add states: `systemAdmin()`, `admin()`, `opsManager()`, `opsStaff()`, `broker()`, `accounting()`, `viewer()`
  - [ ] `systemAdmin()` state: includes email, hashed password, role=SADM
  - [ ] Other states: includes telegram_id, no password, respective role

- [ ] **Task 10: Create `SystemConfigFactory`** (AC: #1)
  - [ ] Run `php artisan make:factory SystemConfigFactory --model=SystemConfig --no-interaction`

- [ ] **Task 11: Install Laravel Fortify for SysAdmin Auth** (AC: #3)
  - [ ] Run `composer require laravel/fortify`
  - [ ] Run `php artisan fortify:install --no-interaction` (if available; otherwise publish config manually)
  - [ ] Configure Fortify for email/password login with TOTP 2FA
  - [ ] Fortify is ONLY for the System Admin (SADM) login — NOT for Telegram users
  - [ ] Update `config/fortify.php`: enable `registration: false` (no self-registration), enable `Features::twoFactorAuthentication()`

- [ ] **Task 12: Configure Dual Auth Guards** (AC: #2, #3)
  - [ ] Update `config/auth.php`:
    - `guards.web` → default Laravel guard for SysAdmin email/password/2FA
    - `guards.telegram` → custom guard for Telegram passwordless auth
  - [ ] Create `TelegramUserProvider` (custom user provider that looks up users by `telegram_id`)
  - [ ] Register the custom guard in `AuthServiceProvider` or `AppServiceProvider`
  - [ ] Both guards use `Session` driver (D10: Redis-backed later, but `database` or `file` for now)

- [ ] **Task 13: Create Telegram Login Controller** (AC: #2)
  - [ ] Create `app/Http/Controllers/Auth/TelegramLoginController.php`
  - [ ] Implement `showLoginPage()` → renders `auth/telegram-login.blade.php`
  - [ ] Implement `handleCallback(Request $request)`:
    - Verify Telegram Login Widget hash using SHA-256 HMAC with bot token (D6)
    - Find user by `telegram_id` in `users` table
    - If found and `is_active`: `Auth::guard('telegram')->login($user)`, update `last_login_at`, log to `audit` channel, redirect to `/dashboard`
    - If not found: flash error "Your Telegram account is not registered. Contact your administrator."
    - If found but `is_active = false`: flash error "Your account has been deactivated."

- [ ] **Task 14: Create System Admin Login Controller** (AC: #3)
  - [ ] Create `app/Http/Controllers/Auth/SystemAdminLoginController.php`
  - [ ] Implement `showLoginForm()` → renders `auth/system-admin-login.blade.php`
  - [ ] Leverage Fortify's authentication + 2FA challenge flow
  - [ ] After successful login + 2FA: update `last_login_at`, log to `audit` channel, redirect to `/dashboard`

- [ ] **Task 15: Create Auth Blade Views** (AC: #2, #3)
  - [ ] Create `resources/views/layouts/auth.blade.php` (minimal layout for login pages — PJL branding, no sidebar)
  - [ ] Create `resources/views/auth/telegram-login.blade.php`:
    - PJL Connect logo and branding
    - Telegram Login Widget (`<script>` from Telegram)
    - Link to "System Admin Login" for SADM users
  - [ ] Create `resources/views/auth/system-admin-login.blade.php`:
    - Email/password form
    - 2FA code input (shown after initial auth via Fortify's two-factor challenge)
    - Link back to "Telegram Login" for regular users
  - [ ] Both views extend `layouts.auth` and use PJL brand colors (Deep Teal, Sky Blue)

- [ ] **Task 16: Configure Auth Routes** (AC: #2, #3, #4)
  - [ ] Add routes in `routes/web.php`:
    - `GET /login` → TelegramLoginController@showLoginPage (named `login`)
    - `GET /auth/telegram/callback` → TelegramLoginController@handleCallback
    - `GET /login/admin` → SystemAdminLoginController@showLoginForm
    - `POST /login/admin` → SystemAdminLoginController (Fortify handles)
    - `POST /logout` → logout for both guards
  - [ ] Protect `/dashboard` route with `auth` middleware
  - [ ] Fortify routes auto-registered for 2FA challenge

- [ ] **Task 17: Create Database Seeder** (AC: #1)
  - [ ] Create/update `database/seeders/DatabaseSeeder.php`
  - [ ] Seed one SADM user: `name: 'System Admin'`, `email: 'admin@pjlconnect.com'`, `password: 'password'`, `role: SADM`
  - [ ] Seed one OPS Staff user (for testing): `name: 'OPS Tester'`, `telegram_id: '123456789'`, `role: OPS`
  - [ ] Seed initial system configs: `nag_interval_minutes: 30`, `silence_threshold_minutes: 60`, `ocr_confidence_auto_accept: 85`

- [ ] **Task 18: Write PHPUnit Tests** (AC: #1–#5)
  - [ ] Create `tests/Feature/Auth/TelegramLoginTest.php` — test Telegram login flow, hash verification, session creation, inactive user rejection, unregistered user rejection
  - [ ] Create `tests/Feature/Auth/SystemAdminLoginTest.php` — test email/password login, 2FA challenge, invalid credentials
  - [ ] Create `tests/Feature/Auth/AuthGuardIsolationTest.php` — test guard isolation (Telegram user can't use web form, SADM can't use Telegram widget)
  - [ ] Create `tests/Feature/Database/MigrationTest.php` — test migrations run, tables exist, columns correct, indexes exist
  - [ ] Create `tests/Unit/Models/UserTest.php` — test UserRole enum casting, UUID auto-generation, factory states, relationships
  - [ ] Create `tests/Unit/Enums/UserRoleTest.php` — test all 7 enum cases exist with correct values
  - [ ] Ensure all existing tests (BoostInstallationTest, LayoutRenderingTest) still pass

- [ ] **Task 19: Run Pint Formatter** (AC: all)
  - [ ] Run `vendor/bin/pint --dirty --format agent`
  - [ ] Fix any formatting issues

## Dev Notes

### Architecture Compliance

> **Source:** [architecture.md — Authentication & Login, Database Design, RBAC, Data Architecture Decisions D1-D27]

- **Auth Architecture:** Dual auth guards — `telegram` (default, passwordless) + `web` (SysAdmin email/password/2FA) [Source: architecture.md#Authentication & Login]
- **Service Pattern:** Controllers → Services → Models. No business logic in controllers [Source: architecture.md#D1]
- **ID Strategy:** `BIGINT UNSIGNED` auto-increment PK + `CHAR(36)` UUID for external IDs [Source: architecture.md#D4]
- **Soft Deletes:** On `users` table (business entity) [Source: architecture.md#D3]
- **Charset:** `utf8mb4` / `utf8mb4_unicode_ci` for Khmer support [Source: architecture.md#D27]
- **Session:** Database-backed sessions for web auth [Source: architecture.md#D10]
- **Linting:** Pint (PSR-12) mandatory [Source: GEMINI.md rules]
- **Testing:** PHPUnit (NOT Pest) per GEMINI.md [Source: GEMINI.md#phpunit/core]

### ⚠️ Critical: Users Table Replaces Laravel Default

Laravel 12 ships with a default `create_users_table` migration. You MUST:

1. **Delete or replace** the default `0001_01_01_000000_create_users_table.php` migration
2. Create a new migration with the custom schema defined in architecture.md (see Task 2)
3. The default migration has `name`, `email` (NOT NULL), `password` (NOT NULL) — our schema makes `email` and `password` NULLABLE (only SADM uses them)
4. Our schema adds: `uuid`, `telegram_id`, `telegram_username`, `role`, `is_active`, `two_factor_secret`, `two_factor_confirmed_at`, `last_login_at`, `deleted_at`

### ⚠️ Critical: Telegram Login Widget Verification

The Telegram Login Widget callback sends a hash that MUST be verified server-side:

```php
// Verification algorithm (from Telegram docs):
// 1. Sort all received fields except 'hash' alphabetically
// 2. Create data-check-string: each field on a new line as "key=value"
// 3. SHA256 hash the bot token to create secret key
// 4. HMAC-SHA256 the data-check-string with the secret key
// 5. Compare with received hash

$secretKey = hash('sha256', config('telegram.bot_token'), true);
$checkHash = hash_hmac('sha256', $dataCheckString, $secretKey);
// $checkHash must equal $request->hash
```

**Security:** Also check `auth_date` is recent (< 86400 seconds old) to prevent replay attacks.

### ⚠️ Critical: Fortify Configuration

- Fortify must be configured for SADM login ONLY
- Set `'registration' => false` — no self-registration
- Enable `Features::twoFactorAuthentication()` for TOTP (Google Authenticator)
- **DO NOT** enable `Features::emailVerification()` — not needed for MVP
- Fortify provides its own routes — ensure they don't conflict with our custom Telegram routes
- Use Fortify's built-in views or override with our branded views

### ⚠️ Critical: Livewire 4 (Not 3)

Story 0.2 discovered that **Livewire 3.x is incompatible with Laravel 12** (requires illuminate/support ^10|^11). The project uses **Livewire 4.2.0** instead. The architecture doc referencing "Livewire 3.x" is outdated — use Livewire 4.x APIs.

[Source: Story 0-2 Debug Log References]

### ⚠️ Critical: SESSION_DRIVER Change

Story 0.1 set `SESSION_DRIVER=file` because no sessions table existed. This story MUST:
1. Create the `sessions` table migration
2. Change `SESSION_DRIVER=database` in `.env`
3. Keep `SESSION_DRIVER=array` in `.env.testing` (test environment stays stateless)

[Source: Story 0-1 learnings]

### Users Table Schema Reference

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `id` | `BIGINT UNSIGNED` | PK, AI | |
| `uuid` | `CHAR(36)` | UNIQUE, NOT NULL | External ID (D4) |
| `name` | `NVARCHAR(255)` | NOT NULL | Khmer script support (D27) |
| `email` | `VARCHAR(255)` | NULLABLE, UNIQUE | Only SysAdmin uses email login |
| `password` | `VARCHAR(255)` | NULLABLE | Only for SADM role |
| `telegram_id` | `VARCHAR(64)` | NULLABLE, UNIQUE | Passwordless auth binding |
| `telegram_username` | `VARCHAR(255)` | NULLABLE | Display reference |
| `role` | `ENUM(SADM,ADM,OPSM,OPS,BRK,ACCT,VIEW)` | NOT NULL | Maps to `UserRole` enum |
| `is_active` | `BOOLEAN` | NOT NULL, DEFAULT `true` | |
| `two_factor_secret` | `TEXT` | NULLABLE | SysAdmin 2FA only |
| `two_factor_confirmed_at` | `TIMESTAMP` | NULLABLE | |
| `last_login_at` | `TIMESTAMP` | NULLABLE | |
| `remember_token` | `VARCHAR(100)` | NULLABLE | Laravel standard |
| `created_at` | `TIMESTAMP` | NULLABLE | |
| `updated_at` | `TIMESTAMP` | NULLABLE | |
| `deleted_at` | `TIMESTAMP` | NULLABLE | Soft delete (D3) |

[Source: architecture.md#Table Definitions — users]

### System Configs Table Schema Reference

| Column | Type | Constraints | Notes |
|--------|------|-------------|-------|
| `id` | `BIGINT UNSIGNED` | PK, AI | |
| `key` | `VARCHAR(128)` | UNIQUE, NOT NULL | e.g., `nag_interval_minutes` |
| `value` | `TEXT` | NOT NULL | |
| `group` | `VARCHAR(64)` | NULLABLE | e.g., `tracking`, `notifications` |
| `description` | `TEXT` | NULLABLE | Admin-facing description (D27) |
| `created_at` | `TIMESTAMP` | NULLABLE | |
| `updated_at` | `TIMESTAMP` | NULLABLE | |

[Source: architecture.md#Table Definitions — system_configs]

### RBAC Role Reference

| # | Role | Code | Auth Method |
|---|------|------|-------------|
| 1 | System Admin | `SADM` | Email + Password + 2FA |
| 2 | Admin | `ADM` | Telegram Passwordless |
| 3 | OPS Manager | `OPSM` | Telegram Passwordless |
| 4 | OPS Staff | `OPS` | Telegram Passwordless |
| 5 | Broker | `BRK` | Telegram Passwordless |
| 6 | Accounting | `ACCT` | Telegram Passwordless |
| 7 | Viewer | `VIEW` | Telegram Passwordless |

[Source: architecture.md#RBAC — Role Model]

### Contextual Logging Requirements (D24)

Login events must include these context fields in the `audit` log channel:

```php
Log::channel('audit')->info('User login successful', [
    'auth_guard'       => 'telegram',  // or 'web'
    'login_method'     => 'telegram_widget',  // or 'email_password_2fa'
    'telegram_user_id' => $user->telegram_id,
    'user_id'          => $user->uuid,
    'user_role'        => $user->role->value,
    'ip'               => $request->ip(),
]);
```

Failed login attempts must also be logged:

```php
Log::channel('audit')->warning('Login attempt failed', [
    'auth_guard'    => 'telegram',
    'reason'        => 'user_not_found',  // or 'inactive', 'invalid_hash', 'invalid_credentials', '2fa_failed'
    'telegram_id'   => $request->input('id'),
    'ip'            => $request->ip(),
]);
```

[Source: architecture.md#D24 Contextual Logging Standard]

### File Structure After This Story

```
app/
├── Enums/
│   └── UserRole.php                    # 7 RBAC roles enum
├── Http/
│   └── Controllers/
│       └── Auth/
│           ├── TelegramLoginController.php
│           └── SystemAdminLoginController.php
├── Models/
│   ├── User.php                        # Updated with HasUuid, SoftDeletes, role cast
│   └── SystemConfig.php
├── Providers/
│   └── AuthServiceProvider.php         # Custom telegram guard registration
└── Traits/
    └── HasUuid.php                     # Auto-generate UUID on create

config/
├── auth.php                            # Dual auth guards: web + telegram
├── database.php                        # utf8mb4 charset and collation
└── fortify.php                         # SysAdmin auth config

database/
├── migrations/
│   ├── xxxx_create_users_table.php     # Custom users table (replaces default)
│   ├── xxxx_create_system_configs_table.php
│   └── xxxx_create_sessions_table.php
├── seeders/
│   └── DatabaseSeeder.php             # SADM + OPS test users + system configs
└── factories/
    ├── UserFactory.php                # With role-based states
    └── SystemConfigFactory.php

resources/views/
├── layouts/
│   └── auth.blade.php                 # Auth layout (no sidebar)
└── auth/
    ├── telegram-login.blade.php       # Telegram Login Widget
    └── system-admin-login.blade.php   # Email/password + 2FA form

tests/
├── Feature/
│   ├── Auth/
│   │   ├── TelegramLoginTest.php
│   │   ├── SystemAdminLoginTest.php
│   │   └── AuthGuardIsolationTest.php
│   └── Database/
│       └── MigrationTest.php
└── Unit/
    ├── Models/
    │   └── UserTest.php
    └── Enums/
        └── UserRoleTest.php
```

### Project Structure Notes

- File locations align with architecture.md's Complete Project Directory Structure [Source: architecture.md#Complete Project Directory Structure]
- `app/Http/Controllers/Auth/TelegramLoginController.php` and `SystemAdminLoginController.php` match architecture's expected locations
- `app/Enums/UserRole.php` is the first enum in the project — sets the pattern for `JobStatus`, `BookingStatus`, etc. in future stories
- `app/Traits/HasUuid.php` is reusable across ALL models with UUIDs (D4)
- `layouts/auth.blade.php` is the login layout — distinct from `layouts/app.blade.php` (the dashboard layout from Story 0.2)
- No Livewire components in this story — auth pages are standard Blade with Telegram JavaScript widget

### Boost Skills to Load

Per architecture.md's Skills Activation Guide, for this story load **only**:
- `MCP` — for schema inspection, Artisan commands, DB access

Do NOT load: Tailwind 4, Livewire, Inertia, Volt, Flux UI, Folio, Pennant, Wayfinder
[Source: architecture.md#Laravel Boost Skills Activation Guide — Phase 0]

### Anti-Pattern Prevention

- ❌ Do NOT use `email` as required field for all users — only SADM needs email
- ❌ Do NOT use `password` as required for all users — only SADM needs password
- ❌ Do NOT install Breeze or Jetstream — they conflict with TailAdmin and our dual auth
- ❌ Do NOT enable Fortify's registration feature — no self-registration
- ❌ Do NOT enable Fortify's email verification — not needed for MVP
- ❌ Do NOT create RBAC middleware — that's Story 0.4
- ❌ Do NOT create sidebar role filtering — that's Story 0.4
- ❌ Do NOT create logging channels or `InjectLogContext` middleware — that's Story 0.5
- ❌ Do NOT install Redis — keep using file/database drivers for now
- ❌ Do NOT install Telescope or Pulse — that's Story 0.5
- ❌ Do NOT create any business logic services (JobService, BookingService, etc.) — not this story
- ❌ Do NOT modify `layouts/app.blade.php` sidebar nav items — sidebar is from Story 0.2
- ✅ DO keep scope: migrations + User model + auth guards + login controllers + auth views + tests
- ✅ DO use Laravel 12 conventions (casts() method, not $casts property)
- ✅ DO handle `NVARCHAR` columns for Khmer support using `$table->string()` with explicit charset if needed (MySQL treats VARCHAR as utf8mb4 when db charset is utf8mb4)

### Previous Story Intelligence

> **Source:** Story 0-2 (0-2-laravel-12-scaffolding-with-tailadmin-integration.md)

**Key learnings from Story 0.2:**
- **Livewire 4.2.0** installed (NOT 3.x) — architecture doc says 3.x but Laravel 12 requires Livewire 4
- **Tailwind CSS 4** uses `@theme` in CSS, NOT `tailwind.config.js` — custom colors already configured
- **Alpine.js 3** initialized in `resources/js/app.js`
- **Layout files created:** `layouts/app.blade.php`, `partials/sidebar.blade.php`, `partials/header.blade.php`, `partials/footer.blade.php`, `dashboard.blade.php`
- **PJL brand colors** already in `resources/css/app.css`: `--color-deep-teal: #1E5A6B`, `--color-sky-blue: #5BC0DE`
- **Inter font** configured as primary
- **.env.testing** uses `array`/`sync`/`array` drivers — maintain this
- **Pint** enforces `php_unit_method_casing` — test methods must be `snake_case` (i.e., `test_*` style)
- **Git commit prefix:** `feat:` for feature work

**Critical from Story 0.1:**
- `SESSION_DRIVER=file` currently — MUST change to `database` after creating sessions table
- **Boost v2.2:** Don't try `boost:skills` command — it doesn't exist

### Git Intelligence

**Recent commits (10 total):**
1. `7e52b3a` — feat: US0-1, Install Laravel Boost, initialize MCP server
2. `2c80460` — feat: Add Laravel Boost installation, MCP server initialization
3. `2368c48` — feat: initial Laravel 12 scaffolding and Laravel Boost installation
4. `a25d17e` — feat: Add initial BMM workflow status tracking
5. `3c148ad` — feat: Add initial planning artifacts

**Patterns observed:**
- Commit prefix: `feat:` for feature work
- All existing tests (17 tests, 37 assertions) must remain passing after this story
- No formatting violations allowed (Pint)

### Testing Requirements

- **Framework:** PHPUnit (NOT Pest — per GEMINI.md rules)
- **Create tests with:** `php artisan make:test Auth/TelegramLoginTest --phpunit`
- **What to test:**
  - Migrations create correct tables with correct columns
  - UserRole enum has all 7 values
  - User model: UUID auto-generation, role casting, factory states
  - Telegram login: hash verification, session creation, inactive user, unregistered user
  - SysAdmin login: email/password, 2FA challenge
  - Auth guard isolation: Telegram guard doesn't accept email login, web guard doesn't accept Telegram
  - Protected routes redirect unauthenticated users to login
  - Login events logged to audit channel with correct context
- **What NOT to test:** RBAC middleware (Story 0.4), business logic, Kanban, bot webhooks
- **Run:** `php artisan test --compact` after all tasks

### Definition of Done

- [ ] Feature works: Both login flows functional (Telegram + SysAdmin)
- [ ] Tests pass: `php artisan test --compact` — all tests pass (existing + new), 0 failures
- [ ] Contextual logging: Login attempts logged to `audit` channel with `auth_guard`, `login_method`, `telegram_user_id` context (D24)
- [ ] Pint: `vendor/bin/pint --dirty --format agent` passes clean
- [ ] Database: `php artisan migrate` creates `users`, `system_configs`, `sessions` tables correctly
- [ ] Seeder: `php artisan db:seed` creates SADM user + OPS test user + system configs

### References

- [Source: architecture.md#Technology Stack] — Full tech stack with versions
- [Source: architecture.md#Authentication & Login] — Dual auth guard design
- [Source: architecture.md#RBAC — Role Model] — 7 roles with auth methods
- [Source: architecture.md#D1-D5] — Data architecture decisions
- [Source: architecture.md#D6] — Telegram webhook SHA-256 HMAC verification
- [Source: architecture.md#D10] — Redis-backed sessions (database for now)
- [Source: architecture.md#D27] — utf8mb4 charset for Khmer support
- [Source: architecture.md#D24] — Contextual logging standard
- [Source: architecture.md#Table Definitions — users] — Users table schema
- [Source: architecture.md#Table Definitions — system_configs] — System configs table schema
- [Source: epics.md#Story 0.3] — Original story definition and acceptance criteria
- [Source: epics.md#Implementation Rules] — Definition of Done checklist
- [Source: ux-design-specification.md#Color System] — Brand color tokens for login pages
- [Source: 0-2-laravel-12-scaffolding-with-tailadmin-integration.md] — Previous story learnings
- [Source: 0-1-install-laravel-boost-and-initialize-mcp-server.md] — Story 0.1 learnings (SESSION_DRIVER)

## Dev Agent Record

### Agent Model Used

{{agent_model_name_version}}

### Debug Log References

### Completion Notes List

### File List
