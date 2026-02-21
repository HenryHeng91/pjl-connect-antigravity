# Story 0.1: Install Laravel Boost and Initialize MCP Server

Status: ready-for-dev

## Story

As a **Developer Agent**,
I want to install Laravel Boost and initialize the MCP Server,
So that all AI coding agents can connect and follow Boost guidelines from Day 1.

## Acceptance Criteria

1. **Given** a fresh Laravel 12 project created via `composer create-project laravel/laravel pjl-connect`
   **When** I run `composer require laravel/boost --dev` and `php artisan boost:install`
   **Then** Laravel Boost is installed and the MCP Server is accessible

2. **Given** Boost is installed
   **When** I run `php artisan boost:skills`
   **Then** available skills are listed without errors

3. **Given** the project is initialized
   **When** the MCP server configuration is checked
   **Then** the Boost MCP server is configured and responding to tool calls

## Tasks / Subtasks

- [ ] **Task 1: Create Fresh Laravel 12 Project** (AC: #1)
  - [ ] Run `composer create-project laravel/laravel pjl-connect`
  - [ ] Verify PHP 8.2+ is active (`php -v`)
  - [ ] Verify Laravel 12.x installed (`php artisan --version`)
  - [ ] Configure `.env` with local database settings (MySQL 8.0+, `utf8mb4` charset)
  - [ ] Run `php artisan key:generate`

- [ ] **Task 2: Install Laravel Boost** (AC: #1, #2)
  - [ ] Run `composer require laravel/boost --dev` (target version 2.1+)
  - [ ] Run `php artisan boost:install`
  - [ ] Verify Boost publishes its configuration and assets
  - [ ] Run `php artisan boost:skills` — confirm output lists available skills
  - [ ] Verify no Composer dependency conflicts

- [ ] **Task 3: Configure and Verify MCP Server** (AC: #3)
  - [ ] Verify MCP server configuration is present (check `config/boost.php` or equivalent)
  - [ ] Confirm MCP server responds to connections
  - [ ] Document MCP server URL/port for other agent configurations
  - [ ] Verify AI agents can query application structure via Boost tools

- [ ] **Task 4: Initialize Git Repository and Base Configuration** (AC: all)
  - [ ] `git init` + initial commit with clean Laravel 12 + Boost
  - [ ] Create `.env.example` with all required env vars documented
  - [ ] Create `.env.testing` for CI pipeline (Story 0.6 will use this)
  - [ ] Ensure `.gitignore` includes standard Laravel exclusions + IDE files

- [ ] **Task 5: Write Verification Tests** (AC: #1, #2, #3)
  - [ ] Create `tests/Feature/BoostInstallationTest.php` using Pest
  - [ ] Test: Boost service provider is registered
  - [ ] Test: `boost:skills` artisan command exists and returns 0 exit code
  - [ ] Test: Application boots without errors after Boost install

## Dev Notes

### Architecture Compliance

> **Source:** [architecture.md — Technology Stack, Core Architectural Decisions]

- **Framework:** Laravel 12.x — bare install via `composer create-project laravel/laravel pjl-connect` (Decision: Bare Laravel 12 + Manual Assembly)
- **PHP:** 8.2+ minimum
- **Laravel Boost:** 2.1+ (latest: 2.1.3 as of Feb 2026) — 🔴 MANDATORY tool per architecture
- **Boost provides:** 15+ specialized tools, documentation API, 17k+ Laravel knowledge base, semantic search, version-aware guidelines
- **Pattern:** Controllers → Services → Models (no Repository pattern for monolith)
- **ID Strategy:** Auto-increment PKs + UUIDs for external-facing IDs (D4)
- **Linting:** Pint (PSR-12) — Laravel 12 default
- **Testing:** PHPUnit + Pest — Laravel 12 default

### Critical Constraints

- This is Task 1 of the entire project — the very first code to be committed
- **No business logic in this story** — pure infrastructure setup
- **No Blade/Livewire components** — those come in Story 0.2
- **No auth guards** — those come in Story 0.3
- **No database migrations** — those come in Story 0.3
- Database connection must be configured but no tables created yet
- Redis NOT required yet (comes with later stories)

### Library/Framework Requirements

| Package | Version | Install Command | Purpose |
|---------|---------|-----------------|---------|
| Laravel | 12.x | `composer create-project laravel/laravel pjl-connect` | Core framework |
| Laravel Boost | 2.1+ | `composer require laravel/boost --dev` | 🔴 MANDATORY — MCP server, AI guidelines |
| Pest | (bundled) | Already included with Laravel 12 | Testing framework |
| Pint | (bundled) | Already included with Laravel 12 | Code linting (PSR-12) |

### File Structure

After this story, the project should have the standard Laravel 12 directory structure with Boost additions:

```
pjl-connect/
├── app/                          # Standard Laravel app directory
├── bootstrap/                    # Framework bootstrap
├── config/
│   ├── boost.php                 # Boost configuration (published by boost:install)
│   └── ...                       # Standard Laravel configs
├── database/                     # Empty migrations dir (no tables yet)
├── public/                       # Web root
├── resources/                    # Views, assets (standard Laravel defaults)
├── routes/                       # Standard web.php, console.php
├── storage/                      # Logs, cache, framework
├── tests/
│   ├── Feature/
│   │   └── BoostInstallationTest.php   # NEW — verify Boost installation
│   └── Pest.php                  # Pest configuration
├── .env.example                  # Environment template with all vars documented
├── .env.testing                  # CI test environment config
├── .gitignore                    # Standard Laravel + IDE exclusions
├── composer.json                 # Laravel 12 + boost dependency
└── ...                           # Standard Laravel root files
```

### Testing Requirements

- **Framework:** Pest (Laravel 12 default)
- **Test location:** `tests/Feature/BoostInstallationTest.php`
- **What to test:**
  - Boost service provider is registered in the application
  - `boost:skills` artisan command exists and returns exit code 0
  - Application boots cleanly (no service provider errors or dependency conflicts)
- **What NOT to test:** No business logic, no auth, no database — those are for later stories
- **Run tests:** `./vendor/bin/pest`

### Definition of Done

- [ ] Feature works: Boost installed, MCP server accessible
- [ ] Tests pass: `./vendor/bin/pest` returns green for all verification tests
- [ ] Contextual logging: N/A (first task — no business logic yet)
- [ ] Git: Clean initial commit with Laravel 12 + Boost

### Anti-Pattern Prevention

- ❌ Do NOT install TailAdmin, Tailwind, Alpine, or Livewire in this story — that's Story 0.2
- ❌ Do NOT create any database migrations — that's Story 0.3
- ❌ Do NOT set up auth guards or RBAC — that's Stories 0.3 and 0.4
- ❌ Do NOT install `irazasyed/telegram-bot-sdk` — that's Epic 1+
- ❌ Do NOT configure Redis — not needed until later stories
- ❌ Do NOT create any Services, Models, or Controllers — no business logic in this story
- ✅ DO keep scope minimal: Laravel 12 + Boost + Git + Tests only

### References

- [Source: architecture.md#Technology Stack] — Full tech stack with versions
- [Source: architecture.md#Starter Template Evaluation] — "Bare Laravel 12 + Manual Assembly" decision
- [Source: architecture.md#Core Architectural Decisions] — D1-D23 decision registry
- [Source: epics.md#Story 0.1] — Original story definition and acceptance criteria
- [Source: epics.md#Implementation Rules] — Task 1 = Install Laravel Boost requirement

## Dev Agent Record

### Agent Model Used

{{agent_model_name_version}}

### Debug Log References

### Completion Notes List

### File List
