---
stepsCompleted: [1, 2, 3, 4]
inputDocuments:
  - '_bmad-output/planning-artifacts/prd.md'
  - '_bmad-output/planning-artifacts/ux-design-specification.md'
  - '_bmad-output/planning-artifacts/product-brief-PJL-Connect-2026-01-24.md'
  - '_bmad-output/analysis/brainstorming-session-2026-01-23.md'
workflowType: 'architecture'
project_name: 'PJL Connect'
user_name: 'Siekhai'
date: '2026-02-14'
---

# Architecture Decision Document — PJL Connect

_This document builds collaboratively through step-by-step discovery. Sections are appended as we work through each architectural decision together._

---

## Project Context Analysis

### Requirements Overview

**52 Functional Requirements** organized into 8 categories:

| Category | FRs | Core Scope |
|----------|-----|-----------|
| Customer Management | FR1–FR3 | Telegram registration, profile binding, OPS management |
| Booking & Shipment | FR4–FR12 | Bot booking, OCR extraction, document upload, Visual Receipt |
| Job Management | FR13–FR24 | Kanban, carrier assignment, status management, document annotation |
| Carrier & Driver | FR25–FR31 | Telegram notifications, Nag-Bot, live location, map tracking |
| Tracking & Monitoring | FR32–FR37 | `/track` command, silence escalation, geofencing, ETA |
| Document & Compliance | FR38–FR42 | ASYCUDA Copy-Paste Magic, SAD review, deadline tracking |
| Financial & Reporting | FR43–FR47 | Invoice generation, QuickBooks export, profit analysis |
| Administration | FR48–FR52 | RBAC, rate cards, carrier DB, system config |

**14 Non-Functional Requirements** driving architecture:

| NFR Domain | Key Constraints |
|------------|----------------|
| **Performance** | Dashboard < 3s, Bot < 2s, Location updates < 10s, OCR < 30s |
| **Reliability** | 99% uptime, 24/7 bot availability, zero data loss |
| **Security** | HTTPS/TLS, RBAC, audit trails, passwordless auth (Telegram ID) |
| **Integration** | Telegram rate limit handling, QuickBooks export format, OCR fallback |

---

### System Module Registry

#### Web Application

| Code | Module Name | Description |
|------|------------|-------------|
| **PJL-BO** | **PJL Connect Back-Office** | Central web dashboard for OPS, Brokers, Accounting, and Management |

#### Telegram Bots

| Code | Module Name | Bot Username (Proposed) | Description |
|------|------------|------------------------|-------------|
| **PJL-CB** | **PJL Customer Bot** | `@PJLConnectBot` | Customer-facing: booking, tracking, documents |
| **PJL-CRB** | **PJL Carrier Bot** | `@PJLCarrierBot` | Carrier dispatch: job acceptance, nag-loop, driver QR |
| **PJL-DB** | **PJL Driver Bot** | `@PJLDriverBot` | Driver execution: location sharing, status updates |
| **PJL-OB** | **PJL OPS Bot** | Group bot in OPS chat | Internal OPS: real-time alerts, exception pings, deep-links |

#### Core Engine

| Code | Module Name | Description |
|------|------------|-------------|
| **PJL-IH** | **PJL Intelligence Hub** | Core engine: OCR pipeline, rule engine, tracking aggregator, SAD mapper, geofencing |

> **Decision:** All Telegram modules are **pure Telegram Bots** (no Mini Apps). Mini App evaluation deferred to Growth/SaaS phase. This aligns with the "Invisible App" philosophy, ensures native file upload for OCR, and keeps architecture simple.

---

### RBAC — Role Model

| # | Role | Code | Permissions | Auth Method |
|---|------|------|-------------|-------------|
| 1 | **System Admin** | `SADM` | 🔑 ALL — full system control, env config, log access, user management | **Email + Password + 2FA** (dedicated Laravel auth) |
| 2 | Admin | `ADM` | All operational modules, no server/env access | Telegram Passwordless |
| 3 | OPS Manager | `OPSM` | Jobs, Booking, Reports, Financial (read-only) | Telegram Passwordless |
| 4 | OPS Staff | `OPS` | Jobs, Booking | Telegram Passwordless |
| 5 | Broker | `BRK` | Jobs (read-only), Compliance | Telegram Passwordless |
| 6 | Accounting | `ACCT` | Financial, Reports | Telegram Passwordless |
| 7 | Viewer | `VIEW` | Dashboard (read-only) | Telegram Passwordless |

---

### Authentication & Login

| Auth Method | Target Roles | Flow |
|-------------|-------------|------|
| **Telegram Passwordless** | ADM, OPSM, OPS, BRK, ACCT, VIEW | User clicks login → Telegram Login Widget confirms identity → Telegram ID matched to user profile → session created |
| **Email + Password + 2FA** | SADM (System Admin) | Traditional Laravel login form with TOTP-based 2FA (e.g., Google Authenticator) |

**Architecture implications:**
- Dual auth guard in Laravel: `telegram` guard (default) + `web` guard (System Admin)
- Session-based auth for both, with CSRF protection
- No password storage for 99% of users
- System Admin has a separate login door — traditional auth ensures highest-privilege account doesn't depend on Telegram

---

### Technology Stack

> [!IMPORTANT]
> **Laravel Boost** and **Pencil.dev** are **MANDATORY** tools for this project.
> - **Laravel Boost**: Must be installed via `php artisan boost:install`. All AI coding agents must connect via the Boost MCP server. Code generation must follow Boost guidelines and skills.
> - **Pencil.dev**: Must be used for all UI design work. `.pen` design files must be committed to Git alongside Blade components. Sequence diagrams must be generated through Pencil.dev.

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| **Framework** | Laravel | 12.x | Core backend + web application |
| **PHP** | PHP | 8.2+ | Server runtime |
| **Frontend Rendering** | Livewire | 3.x | Real-time components (Kanban, maps, tables) |
| **CSS Framework** | Tailwind CSS | 4.x | Utility-first styling |
| **Micro-Interactions** | Alpine.js | 3.x | Client-side reactivity (dropdowns, modals, toasts) |
| **UI Kit** | TailAdmin Laravel | Free Edition | Dashboard template, tables, forms, 27 base components |
| **Kanban** | Filament Kanban | Latest | Drag-drop job management boards |
| **Maps** | Leaflet.js | 1.9+ | GPS tracking maps with geofence visualization |
| **PDF Viewer** | PDF.js | Latest | In-browser document preview & annotation |
| **Charts** | ApexCharts | 3.x | Dashboard analytics & trend charts |
| **Database** | MySQL / MariaDB | 8.0+ / 10.6+ | Primary data store |
| **Cache / Queue** | Redis | 7.x | Job queues, real-time pub/sub, session cache |
| **Queue Worker** | Laravel Horizon | Latest | Queue monitoring & management |
| **OCR** | Google Cloud Vision API | v1 | Document text extraction |
| **Telegram** | Telegram Bot API | Latest | Bot communication layer |
| **Telegram Library** | irazasyed/telegram-bot-sdk | 3.x | Laravel Telegram bot integration |
| **Auth (Passwordless)** | Telegram Login Widget | — | Passwordless auth for most roles |
| **Auth (System Admin)** | Laravel Fortify + 2FA | — | Email/password + TOTP for System Admin |
| **AI Dev Tooling** | **Laravel Boost** | **2.1+** | 🔴 MANDATORY — MCP server, AI guidelines, skills |
| **Design Tooling** | **Pencil.dev** | Latest | 🔴 MANDATORY — AI-native design-to-code, `.pen` files |
| **Web Server** | Nginx | Latest | Reverse proxy + static asset serving |
| **Deployment** | Docker / VPS | — | Containerized deployment |

---

### Key Architectural Aspects

1. **Hybrid Architecture (Web + Bots):** The system straddles two paradigms — a Laravel web Back-Office (PJL-BO) AND 4 pure Telegram Bots. The bots have rich command hierarchies, OCR flows, nag-loops, and location streaming.

2. **Real-Time Requirements:** Kanban auto-updates, GPS map tracking, geofence triggers, silence escalation, nag-bot timers — demand event-driven architecture alongside standard request-response.

3. **AI/OCR Pipeline:** Document ingestion → Google Cloud Vision API → Confidence routing → Human verification loop. Async pipeline requiring job queue architecture.

4. **Exception-First Design:** "99% happy path / 1% human exception" pattern. Architecture must support escalation chains, manual overrides with audit trails, and graceful degradation.

5. **UX State Contract:** Every screen defines 4 states (Empty/Loading/Error/Success). API layer must support all 4 states — consistent error formats, pagination metadata for empty detection, loading-optimized endpoints.

6. **45 UI Components:** 27 from TailAdmin, 11 custom, 7 Livewire-driven. Kanban and GPS map require Livewire/Alpine.js real-time patterns.

---

### Scale & Complexity Assessment

| Indicator | Rating | Rationale |
|-----------|--------|-----------|
| **Overall Complexity** | **High** | Multi-channel (web + 4 bots), real-time tracking, OCR pipeline, RBAC |
| **Primary Domain** | **Full-Stack + Bot Integration** | Laravel web + Telegram Bot API + Cloud AI |
| **Real-Time Features** | **Medium-High** | GPS tracking, Kanban updates, geofencing, nag timers |
| **Multi-Tenancy** | **Low (MVP)** | Single-tenant for PJL; SaaS deferred to Year 2 |
| **Regulatory/Compliance** | **Medium** | ASYCUDA format compliance, audit trails, RBAC |
| **Integration Complexity** | **Medium** | Telegram API, Google Cloud Vision, QuickBooks export |
| **Data Complexity** | **High** | Multi-leg shipments, document versioning, GPS streams, financial ledger |

---

### Technical Constraints & Dependencies

| Constraint | Decision | Arch Impact |
|------------|----------|-------------|
| **Laravel 12** | Latest version, supported by Laravel Boost 2.1+ | Monolithic with service layers |
| **Telegram-First** | No app installations, pure bots | Bot architecture as first-class citizen |
| **8-Hour Location Limit** | Telegram API constraint | Re-prompt logic and state management |
| **Single-Tenant MVP** | PJL only | No multi-tenant abstractions needed |
| **USD-Only MVP** | No multi-currency | Simplified financial model |
| **Manual Vessel/Flight** | No external tracking APIs | OPS manual entry patterns |
| **ASYCUDA Copy-Paste** | No direct API integration | Formatted data block generation |

---

### Cross-Cutting Concerns

1. **Audit Trail** — All jobs, financials, config changes, manual overrides
2. **Notification System** — Web toasts + Telegram bot messages + OPS group alerts
3. **RBAC** — 7 roles (System Admin, Admin, OPS Manager, OPS Staff, Broker, Accounting, Viewer)
4. **Authentication** — Dual auth guards: Telegram Passwordless (default) + Email/Password/2FA (System Admin only)
5. **Job Status State Machine** — Central lifecycle shared across Kanban, bots, tracking, compliance
6. **Document Management** — Upload, OCR, preview, annotation, ASYCUDA mapping
7. **Error Handling** — Consistent patterns supporting all 4 UI states across web + bots

---

### Planned Architecture Deliverables

**Phase 0: Environment Setup** — will be defined as the first implementation milestone:
- Laravel 12 scaffolding + `php artisan boost:install`
- Laravel Boost MCP server configuration
- Pencil.dev workspace setup + `.pen` file structure
- TailAdmin template integration
- Tailwind CSS + Alpine.js + Livewire configuration
- Database + Redis setup
- Telegram Bot API tokens + webhook configuration
- RBAC seeding (7 roles) + dual auth guard configuration
- CI/CD pipeline baseline

**Component-Data Map** — will trace every UX component to its data source:
- All 45 components from UX spec mapped to Controller → Service → Model(s)
- Ensures no component is orphaned from its data pipeline

**Pencil.dev Sequence Diagrams** — all architectural flows will be authored in Pencil.dev:
- J1–J7 journey sequence diagrams
- API interaction diagrams (Bot ↔ Intelligence Hub ↔ Back-Office)
- Auth flow diagrams (Telegram Passwordless + System Admin 2FA)
- OCR pipeline flow diagram

---

## Starter Template Evaluation

### Primary Technology Domain

**Full-Stack Web + Bot Integration** — Laravel 12 monolith serving both the Back-Office web application and Telegram Bot webhook handlers, with shared service layer and database.

### Starter Options Considered

| # | Starter | Init Method | Verdict |
|---|---------|-------------|---------||
| 1 | Laravel 12 Official Livewire Starter Kit | `laravel new` → select Livewire | ❌ Uses Flux UI (conflicts with TailAdmin) |
| 2 | TailAdmin Laravel (Free) | `git clone` | ❌ No auth, no Livewire, incomplete foundation |
| 3 | Filament v4 | `composer require filament/filament` | ❌ Opinionated admin panel, conflicts with TailAdmin |
| 4 | Community Starters (`--using` flag) | `laravel new --using=...` | ❌ None match exact stack requirements |
| 5 | **Bare Laravel 12 + Manual Assembly** | `composer create-project` | ✅ **Selected** — total control, no conflicts |

### Selected Starter: Bare Laravel 12 + Manual Assembly

**Rationale:**
- No UI kit conflicts — TailAdmin is clone-based, not a Composer package
- Laravel Boost `php artisan boost:install` works cleanest on fresh install
- Dual auth guards (Telegram + System Admin 2FA) don't match any starter's auth
- Phase 0 defines the exact assembly sequence
- Total control over every architectural decision

**Initialization Command:**

```bash
composer create-project laravel/laravel pjl-connect
```

**Architectural Decisions Provided by Starter:**

| Decision | Choice | Source |
|----------|--------|--------|
| Language & Runtime | PHP 8.2+ | Laravel 12 minimum |
| Styling | Tailwind CSS 4.x | Added via TailAdmin integration |
| Build Tooling | Vite | Laravel 12 default |
| Testing | PHPUnit + Pest | Laravel 12 default |
| Linting | Pint (PSR-12) | Laravel 12 default |
| Code Organization | Laravel conventions + service layer | Controllers → Services → Models |
| Auth | Laravel Fortify + custom guards | Dual auth (Telegram + traditional) |
| Queue | Redis + Laravel Horizon | Async jobs, OCR pipeline, nag-bot |

> **Note:** Project initialization using this command will be the first task in Phase 0: Environment Setup.

---

## Core Architectural Decisions

### Decision Priority Analysis

**Critical Decisions (Block Implementation):**
- D1–D5: Data architecture (Eloquent + Service Layer, migrations, soft deletes, UUID strategy, Redis caching)
- D6–D7, D9–D10: Security (webhook verification, CSRF, rate limiting, sessions)
- D11–D13: Communication (no separate API, standardized error envelope, Laravel Events)

**Important Decisions (Shape Architecture):**
- D14–D15: File storage (local disk) and logging (channel-based)
- D16–D19: Frontend architecture (Livewire full-page, Alpine state, polling + WebSocket, toast system)
- D20–D23: Infrastructure (Hostinger VPS, GitHub Actions, env config, Pulse monitoring)

**Removed Decisions:**
- ~~D8: Encryption at rest~~ — Removed for MVP. Rely on HTTPS + MySQL permissions + RBAC.
- ~~D24: Spatie Backup~~ — Removed. Hostinger provides built-in DB backup.

**Deferred Decisions (Post-MVP):**
- Multi-tenancy architecture (SaaS Year 2)
- API versioning (no external consumers in MVP)
- S3/cloud file storage (local sufficient for single-tenant)
- Multi-currency support (USD-only MVP)
- Kubernetes/scaling (single VPS sufficient)
- Telegram Mini App (Bot-only for MVP)
- External tracking APIs (manual vessel/flight entry)

---

### Data Architecture

| # | Decision | Choice | Rationale |
|---|----------|--------|----------|
| D1 | Data Modeling | Eloquent Models + Service Layer | Controllers → Services → Models. No Repository pattern for monolith. |
| D2 | Migration Strategy | Incremental migrations + seeders | `php artisan migrate`. Feature-flag for zero-downtime schema changes. |
| D3 | Soft Deletes | Yes, on all business entities | Jobs, Bookings, Customers, Documents, Invoices. Audit trail requirement. |
| D4 | ID Strategy | UUIDs for external-facing IDs, auto-increment for internal PKs | Performance of integer PKs + security of UUIDs for public URLs/bot deep-links. |
| D5 | Caching | Redis + Laravel Cache facade | Model cache tags per entity, 15-min TTL for dashboard stats, real-time invalidation for Kanban. |

### Authentication & Security

| # | Decision | Choice | Rationale |
|---|----------|--------|----------|
| D6 | Bot Webhook Security | Telegram SHA-256 HMAC signature verification + IP whitelist | Verify on every webhook. Whitelist Telegram IP ranges. |
| D7 | CSRF | Standard Laravel CSRF for web, disabled for bot webhook routes | Bots use signature verification instead. |
| D9 | Rate Limiting | Laravel built-in rate limiter | 60 req/min web, 120 req/min API, respect Telegram 30 msg/sec limit. |
| D10 | Sessions | Redis-backed, 8-hour expiry for web, stateless for bots | Fast session lookups. Bots don't need sessions. |

### API & Communication Patterns

| # | Decision | Choice | Rationale |
|---|----------|--------|----------|
| D11 | Internal API | No separate API layer for MVP | Livewire + Bot controllers both call shared Service layer directly. |
| D12 | Error Response Format | Standardized JSON envelope | `{success, data, error: {code, message, field_errors}, meta: {pagination, empty_reason}}` — supports all 4 UI states. |
| D13 | Event System | Laravel Events + Listeners + Redis pub/sub | Job status → fires event → bot notifier + audit logger + Kanban updater. Decoupled modules. |
| D14 | File Storage | Laravel Filesystem (local disk MVP) | `storage/app/documents/{job_id}/`. Migrate to S3 in Growth phase. |
| D15 | Logging | Laravel Log + daily rotation, channel-based | Channels: `web`, `bot`, `ocr`, `queue`. Structured JSON in production. |

### Frontend Architecture

| # | Decision | Choice | Rationale |
|---|----------|--------|----------|
| D16 | Component Architecture | Full-page Livewire for screens, Blade for reusable UI, Alpine for micro-interactions | Real-time updates without page reload. TailAdmin integration via Blade. |
| D17 | State Management | Livewire properties (server), Alpine x-data (UI-only), Session/Cache (cross-request) | No Vuex/Pinia needed. Livewire handles server state natively. |
| D18 | Real-Time Updates | Livewire polling (3s) for Kanban/Dashboard, Laravel Echo + Soketi for GPS map | Polling for most screens. True WebSocket only where < 10s latency required. |
| D19 | Notifications | Alpine.js toast system + Livewire dispatch | Auto-dismiss success. Errors persist until acknowledged. |

### Infrastructure & Deployment

| # | Decision | Choice | Rationale |
|---|----------|--------|----------|
| D20 | Hosting | **Hostinger VPS KVM 2** (2 vCPU, 8GB RAM, 100GB NVMe) | ~$8-14/mo. Laravel template available. Redis via SSH. |
| D21 | CI/CD | GitHub Actions | Lint → Test → Build → Deploy via SSH. Script provided below. |
| D22 | Environment Config | `.env` + `php artisan config:cache` | Secrets in `.env`, never in code. |
| D23 | Monitoring | Laravel Pulse (free, `/pulse` route) + UptimeRobot (external) | Pulse for app metrics. UptimeRobot for uptime alerts. Restricted to SADM/ADM. |

### GitHub Actions Deploy Script

```yaml
# .github/workflows/deploy.yml
name: Deploy PJL Connect

on:
  push:
    branches: [main]
  pull_request:
    branches: [main]

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - run: composer install --no-interaction
      - run: ./vendor/bin/pint --test

  test:
    needs: lint
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: password
          MYSQL_DATABASE: pjl_connect_test
        ports: ['3306:3306']
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3
      redis:
        image: redis:7
        ports: ['6379:6379']
        options: --health-cmd="redis-cli ping" --health-interval=10s --health-timeout=5s --health-retries=3
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: pdo_mysql, redis
      - run: composer install --no-interaction
      - run: cp .env.testing .env
      - run: php artisan key:generate
      - run: php artisan migrate --force
      - run: ./vendor/bin/pest

  build:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
      - run: npm ci
      - run: npm run build
      - uses: actions/upload-artifact@v4
        with:
          name: build-assets
          path: public/build/

  deploy:
    needs: build
    if: github.ref == 'refs/heads/main' && github.event_name == 'push'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/download-artifact@v4
        with:
          name: build-assets
          path: public/build/
      - name: Deploy to Hostinger VPS
        uses: appleboy/ssh-action@v1
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USER }}
          key: ${{ secrets.VPS_SSH_KEY }}
          script: |
            cd /var/www/pjl-connect
            git pull origin main
            composer install --no-dev --optimize-autoloader
            php artisan migrate --force
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            php artisan queue:restart
            sudo systemctl reload nginx
```

### Decision Impact — Implementation Sequence

1. **Phase 0:** Laravel 12 + Boost install + TailAdmin + Redis + auth guards + RBAC seed
2. **Data Layer:** Eloquent models + migrations + soft deletes + UUID traits
3. **Service Layer:** Services for each module (JobService, BookingService, etc.)
4. **Event System:** Laravel Events + Listeners for job lifecycle
5. **Bot Layer:** Telegram webhook controllers + signature verification
6. **Frontend:** Livewire full-page components + TailAdmin Blade components
7. **Real-Time:** Polling for Kanban/Dashboard, Echo + Soketi for GPS map
8. **CI/CD:** GitHub Actions pipeline + Hostinger VPS deployment
