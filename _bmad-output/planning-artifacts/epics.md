---
stepsCompleted: [1, 2, 3, 4]
inputDocuments:
  - '_bmad-output/planning-artifacts/prd.md'
  - '_bmad-output/planning-artifacts/architecture.md'
  - '_bmad-output/planning-artifacts/ux-design-specification.md'
workflowType: 'create-epics-and-stories'
project_name: 'PJL Connect'
user_name: 'Siekhai'
date: '2026-02-21'
---

# Epic Breakdown Document — PJL Connect

## Extracted Requirements

### Functional Requirements (52 FRs)

| Category | FRs | Summary |
|----------|-----|---------|
| Customer Management | FR1–FR3 | Telegram registration, Telegram ID → profile binding, OPS profile management |
| Booking & Shipment | FR4–FR12 | `/new` booking, service selection, document upload (any format), OCR extraction, data confirmation, Visual Receipt Card, filterable list, OPS manual booking, document attach |
| Job Management | FR13–FR24 | Booking→Job conversion, Kanban view, carrier assignment, status override, comments, unique ref numbers, auto/manual OPS assignment, document print/preview/annotation, booking summary print |
| Carrier & Driver | FR25–FR31 | Telegram job notifications, accept/decline, Nag-Bot, OPS escalation, live location sharing, real-time tracking, map view |
| Tracking & Monitoring | FR32–FR37 | `/track` command, blackout detection, Silence Escalation, manual vessel/flight entry, ETA calculation, geofence events |
| Document & Compliance | FR38–FR42 | Document view per job, ASYCUDA Copy-Paste Magic, SAD review, deadline tracking, customer reminders |
| Financial & Reporting | FR43–FR47 | Invoice generation, QuickBooks export, management dashboard, profit per booking, audit trail |
| Administration | FR48–FR52 | User accounts, RBAC, rate cards, carrier database, system settings |

### Non-Functional Requirements (14 NFRs)

| Domain | NFRs | Key Constraints |
|--------|------|-----------------|
| Performance | NFR1–NFR4 | Dashboard <3s, Bot <2s, Location <10s, OCR <30s |
| Reliability | NFR5–NFR7 | 99% uptime, 24/7 bot, zero data loss |
| Security | NFR8–NFR11 | HTTPS/TLS, RBAC, audit trail, passwordless auth |
| Integration | NFR12–NFR14 | Telegram rate limits, QuickBooks format, OCR fallback |

### Additional Requirements (Architecture & UX)

- **Phase 0 Environment Setup:** Laravel 12 scaffolding, Boost install, TailAdmin, Redis, dual auth guards, RBAC seed, CI/CD
- **Dual Auth Guards:** Telegram Passwordless (default) + Email/Password/2FA (System Admin)
- **Event-Driven Architecture:** Laravel Events + Listeners for job lifecycle
- **Day 2 Operations:** Contextual logging per D24, exception mapping, health checks, Pulse
- **Database Design:** 16 tables, utf8mb4 charset (Khmer support D27), migration ordering
- **Responsive Parity:** Desktop & mobile Back-Office identical capabilities
- **4 UI States:** Empty/Loading/Error/Success per screen (UX State Logic Matrix)
- **Exception-First Design:** Dashboard surfaces problems, hides routine
- **Hybrid Visual Strategy:** Clean Operations for Jobs, Modern Logistics for Dashboard
- **Component Library:** 45 components (27 TailAdmin, 11 custom, 7 Livewire)
- **Accessibility:** WCAG AA, focus rings, 44x44px touch targets

---

## Implementation Rules

> **Strict rules applied to ALL epics and stories:**

1. **Implementation Units:** Every story must include: (1) Migration, (2) Model/Logic, (3) Blade/Livewire Component, (4) Pest/PHPUnit Test
2. **Task 1:** First task in backlog = "Install Laravel Boost (`composer require laravel/boost --dev`) and initialize the MCP Server"
3. **Visual Acceptance:** All frontend tasks must include: "Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact"
4. **Definition of Done:** A task is not 'Done' until: (1) The feature works, (2) The unit tests pass, (3) Contextual logging has been implemented for the core logic as per the Architect's plan (D24)

---

## Epic List

### Epic 0: Project Foundation & Environment Setup

**User Outcome:** Development team has a working, deployable Laravel 12 environment with all tooling, authentication, RBAC, and observability infrastructure ready.

**FRs covered:** Foundational — enables ALL FRs
**NFRs addressed:** NFR5 (uptime foundation), NFR8 (HTTPS/TLS), NFR9 (RBAC), NFR11 (passwordless auth)

**Implementation Units:**
- **Task 1:** Install Laravel Boost (`composer require laravel/boost --dev`) and initialize the MCP Server
- Migrations: `users`, `system_configs` tables + RBAC role seed
- Auth: Dual guards — Telegram Passwordless + SysAdmin Email/Password/2FA
- TailAdmin template integration + Tailwind CSS 4 + Alpine.js 3 + Livewire 3
- CI/CD pipeline (GitHub Actions: lint → test → build → deploy)
- Day 2 Ops: Health check endpoint (`/up`), logging channels, Telescope (dev), Pulse (prod)
- Contextual logging middleware (`InjectLogContext`)
- Exception handler with domain exception mapping (D24)

---

### Epic 1: Customer Registration & Profile Management

**User Outcome:** Customers can register via Telegram `/start` command, get their Telegram ID auto-bound to a customer profile (passwordless auth), and OPS can view, create, and manage all customer profiles in the Back-Office.

**FRs covered:** FR1, FR2, FR3
**NFRs addressed:** NFR2 (bot <2s), NFR11 (passwordless auth)

**Implementation Units per story:**
- Migration: `customers` table
- Model + Service: `Customer` model, `CustomerService`, `CustomerProfileService`
- Blade/Livewire: Customer list/detail views in Back-Office
- Telegram: `CustomerBot/Commands/StartCommand`
- Pest: Unit + Feature tests

---

### Epic 2: Booking & Document Intake

**User Outcome:** Customers can create bookings via Telegram `/new` command, select service type (Truck/Sea/Air/Multi-leg), upload documents in ANY format (PDF/Excel/Word/Image), get OCR extraction with confidence-based routing, confirm or correct extracted data, and receive a Visual Receipt Card. OPS can view all bookings in a filterable list, manually create bookings, and attach shipping documents.

**FRs covered:** FR4, FR5, FR6, FR7, FR8, FR9, FR10, FR11, FR12
**NFRs addressed:** NFR2 (bot <2s), NFR4 (OCR <30s), NFR14 (OCR fallback)

**Implementation Units per story:**
- Migration: `bookings`, `documents` tables
- Model + Service: `Booking`, `Document` models, `BookingService`, `BookingValidationService`, `OcrExtractionService`
- Blade/Livewire: `BookingList`, `BookingWizard` components
- Telegram: `CustomerBot/Commands/BookCommand`, `CustomerBot/Handlers/BookingFlowHandler`, `CustomerBot/Handlers/DocumentUploadHandler`
- Queue: OCR pipeline job (Google Cloud Vision API)
- Pest: Unit + Feature tests

---

### Epic 3: Job Management & OPS Command Center

**User Outcome:** OPS can manage the entire job lifecycle from a central command center: confirmed bookings auto-convert to jobs with unique reference numbers, Kanban board shows jobs by status with real-time drag-drop, carriers can be assigned, statuses can be manually overridden, comments/notes are tracked, and documents can be printed, previewed in-browser, and annotated with remarks (red text, circles, rectangles).

**FRs covered:** FR13, FR14, FR15, FR16, FR17, FR18, FR19, FR20, FR21, FR22, FR23, FR24
**NFRs addressed:** NFR1 (dashboard <3s), NFR7 (zero data loss), NFR10 (audit trail)

**Implementation Units per story:**
- Migration: `jobs`, `shipment_legs` tables
- Model + Service: `Job`, `ShipmentLeg` models, `JobService`, `JobStatusService`, `JobAssignmentService`, `JobDocumentService`
- Blade/Livewire: `KanbanBoard`, `JobDetail`, `JobTimeline` components + Filament Kanban
- Events: `JobCreated`, `JobStatusUpdated`, `JobAssigned`, `JobCompleted`
- Listeners: `NotifyCarrierOnAssignment`, `UpdateKanbanOnStatusChange`, `LogJobAuditTrail`, `AlertOpsOnException`
- Pest: Unit + Feature + Livewire tests

---

### Epic 4: Carrier & Driver Operations

**User Outcome:** Carriers receive job notifications via Telegram, can accept or decline jobs with inline buttons, Nag-Bot sends repeated notifications until response, and OPS gets escalation when carrier doesn't respond within threshold. Drivers share live GPS location via Telegram, and OPS can view driver positions on a map.

**FRs covered:** FR25, FR26, FR27, FR28, FR29, FR30, FR31
**NFRs addressed:** NFR2 (bot <2s), NFR3 (location <10s), NFR6 (24/7 bot), NFR12 (Telegram rate limits)

**Implementation Units per story:**
- Migration: `carriers`, `drivers`, `location_pings` tables
- Model + Service: `Carrier`, `Driver`, `LocationPing` models, `CarrierService`, `DriverService`, `NagBotService`
- Telegram: `CarrierBot/` (job acceptance, nag-loop), `DriverBot/` (location sharing, status updates)
- Events: `CarrierJobAccepted`, `DriverLocationUpdated`
- Queue: Nag-Bot scheduled job
- Pest: Unit + Feature tests

---

### Epic 5: Tracking & Live Monitoring

**User Outcome:** Customers can track shipment status via `/track` command and see real-time location updates. OPS sees live GPS on a Cambodia map with clickable driver markers, gets automatic Silence Escalation alerts when GPS goes dark, and geofences auto-trigger arrival/departure events. ETA is dynamically calculated based on route and current position.

**FRs covered:** FR32, FR33, FR34, FR35, FR36, FR37
**NFRs addressed:** NFR3 (location <10s), NFR6 (24/7 bot)

**Implementation Units per story:**
- Migration: `geofences` table
- Model + Service: `Geofence` model, `TrackingService`, `GeofenceService`, `EtaCalculationService`
- Blade/Livewire: `LiveMap` (Leaflet.js), `TrackingDetail` components
- Telegram: `CustomerBot/Commands/TrackCommand`
- Real-time: Laravel Echo + Soketi WebSocket for GPS map updates
- Events: Silence Escalation event chain
- Pest: Unit + Feature tests

---

### Epic 6: Document & Customs Compliance

**User Outcome:** Brokers can view all documents attached to any job, use one-click Copy-Paste Magic to generate formatted ASYCUDA data blocks, review SAD data before submission, and the system auto-tracks document submission deadlines (based on ETD) and sends reminders to customers for pending documents.

**FRs covered:** FR38, FR39, FR40, FR41, FR42
**NFRs addressed:** NFR10 (audit trail)

**Implementation Units per story:**
- Migration: `sad_declarations` table
- Model + Service: `SadDeclaration` model, `AsycudaService`, `SadReviewService`, `DeadlineService`
- Blade/Livewire: `AsycudaWorkbench`, `DeadlineTracker` components
- Custom component: Copy-Paste Magic Block (`<x-copy-block>`)
- Queue: Deadline reminder scheduled job
- Pest: Unit + Feature tests

---

### Epic 7: Financial & Reporting

**User Outcome:** OPS can generate invoices for completed jobs with auto-populated line items. Accounting can export financial data to QuickBooks-compatible format. Management sees a dashboard with key metrics (booking volume, revenue, margin trends). Profit per booking is auto-calculated. Full job history and audit trail is viewable.

**FRs covered:** FR43, FR44, FR45, FR46, FR47
**NFRs addressed:** NFR1 (dashboard <3s), NFR10 (audit trail), NFR13 (QuickBooks format)

**Implementation Units per story:**
- Migration: `invoices`, `invoice_line_items`, `rate_cards` tables
- Model + Service: `Invoice`, `InvoiceLineItem`, `RateCard` models, `InvoiceService`, `QuickBooksExportService`, `ProfitAnalysisService`
- Blade/Livewire: `InvoiceManager`, `ProfitDashboard` components + ApexCharts
- Pest: Unit + Feature tests

---

### Epic 8: Administration & System Configuration

**User Outcome:** Admins can create and manage user accounts, assign RBAC roles and permissions, configure rate cards and pricing, manage the carrier database, and adjust system settings (nag intervals, thresholds, etc.) — all from the Back-Office.

**FRs covered:** FR48, FR49, FR50, FR51, FR52
**NFRs addressed:** NFR9 (RBAC enforcement)

**Implementation Units per story:**
- Model + Service: `RbacService`, `RateCardService`, `SystemConfigService`
- Blade/Livewire: `UserManager`, `RateCardEditor`, `SystemConfig` components
- Pest: Unit + Feature tests

---

### Epic 9: Cross-Cutting Quality & Observability

**User Outcome:** System is production-hardened: all audit trails logged and queryable, performance meets every NFR threshold, exceptions degrade gracefully with user-friendly messages, and event-driven notification chains work end-to-end.

**NFRs addressed:** NFR1–NFR14 (all verified and hardened)

**Implementation Units:**
- Migration: `audit_logs` table
- Model + Service: `AuditLog` model, `AuditService`, `NotificationService`, `FileStorageService`
- Integration: Event → Listener → Notification chain tests
- Performance: Load testing against NFR thresholds (dashboard <3s, bot <2s, location <10s, OCR <30s)
- Exception handler: Verify all domain exceptions → correct HTTP codes + user-friendly messages
- Pest: Architectural tests (no direct model calls from controllers, etc.)

---

## Epic Dependency Flow

```
Epic 0 (Foundation) → Required by ALL
Epic 1 (Customers) → Required by Epic 2, 7
Epic 2 (Booking) → Required by Epic 3
Epic 3 (Jobs) → Required by Epic 4, 5, 6, 7
Epic 4 (Carrier/Driver) → Enhances Epic 5
Epic 5 (Tracking) → Standalone (uses 3, 4)
Epic 6 (Compliance) → Standalone (uses 3)
Epic 7 (Financial) → Standalone (uses 1, 3)
Epic 8 (Admin) → Can run in parallel after Epic 0
Epic 9 (Quality/Observability) → Continuous across all epics
```

---

## Requirements Coverage Map

### FR Coverage Map

| FR | Epic | Brief Description |
|----|------|-------------------|
| FR1 | Epic 1 | Customer registers via Telegram `/start` |
| FR2 | Epic 1 | System binds Telegram ID to customer profile |
| FR3 | Epic 1 | OPS creates/manages customer profiles in Back-Office |
| FR4 | Epic 2 | Customer creates booking via `/new` command |
| FR5 | Epic 2 | Customer selects service type (Truck/Sea/Air/Multi-leg) |
| FR6 | Epic 2 | Customer uploads documents in any format |
| FR7 | Epic 2 | System extracts booking data via OCR |
| FR8 | Epic 2 | Customer confirms or corrects OCR data |
| FR9 | Epic 2 | Customer receives Visual Receipt Card |
| FR10 | Epic 2 | OPS views bookings in filterable list |
| FR11 | Epic 2 | OPS manually creates bookings |
| FR12 | Epic 2 | OPS attaches shipping documents to booking/job |
| FR13 | Epic 3 | System converts confirmed bookings into jobs |
| FR14 | Epic 3 | OPS views jobs in Kanban format |
| FR15 | Epic 3 | OPS assigns carriers to jobs |
| FR16 | Epic 3 | OPS manually overrides job status |
| FR17 | Epic 3 | OPS adds comments/notes to jobs |
| FR18 | Epic 3 | System generates unique job reference numbers |
| FR19 | Epic 3 | System auto-assigns bookings to available OPS |
| FR20 | Epic 3 | OPS Manager manually assigns bookings to staff |
| FR21 | Epic 3 | OPS prints all documents attached to a job |
| FR22 | Epic 3 | OPS views all document formats in-browser |
| FR23 | Epic 3 | OPS annotates PDF documents with remarks |
| FR24 | Epic 3 | OPS prints booking summary cover page |
| FR25 | Epic 4 | Carrier receives job notifications via Telegram |
| FR26 | Epic 4 | Carrier accepts or declines jobs |
| FR27 | Epic 4 | Nag-Bot sends repeated notifications |
| FR28 | Epic 4 | OPS gets escalation on carrier non-response |
| FR29 | Epic 4 | Driver shares live location via Telegram |
| FR30 | Epic 4 | System tracks driver location in real-time |
| FR31 | Epic 4 | OPS views driver location on map |
| FR32 | Epic 5 | Customer tracks shipment via `/track` |
| FR33 | Epic 5 | System detects location sharing blackouts |
| FR34 | Epic 5 | System alerts OPS on driver silence |
| FR35 | Epic 5 | OPS manually enters vessel/flight tracking |
| FR36 | Epic 5 | System calculates ETA |
| FR37 | Epic 5 | System detects geofence entry/exit events |
| FR38 | Epic 6 | OPS views all documents attached to a job |
| FR39 | Epic 6 | System generates ASYCUDA formatted data blocks |
| FR40 | Epic 6 | Broker reviews SAD data before submission |
| FR41 | Epic 6 | System tracks document submission deadlines |
| FR42 | Epic 6 | Customer receives reminders for pending documents |
| FR43 | Epic 7 | OPS generates invoices for completed jobs |
| FR44 | Epic 7 | Accounting exports to QuickBooks format |
| FR45 | Epic 7 | Management views dashboard with key metrics |
| FR46 | Epic 7 | System calculates profit per booking |
| FR47 | Epic 7 | OPS views job history and audit trail |
| FR48 | Epic 8 | Admin creates/manages user accounts |
| FR49 | Epic 8 | Admin assigns roles/permissions (RBAC) |
| FR50 | Epic 8 | Admin configures rate cards and pricing |
| FR51 | Epic 8 | Admin manages carrier database |
| FR52 | Epic 8 | Admin configures system settings |

### NFR Coverage Map

| NFR | Epics | Enforcement |
|-----|-------|-------------|
| NFR1 | Epic 0, 3, 7 | Dashboard loads <3s — performance budget in Livewire components |
| NFR2 | Epic 0, 1, 2, 4 | Telegram bot responds <2s — async queue for heavy ops |
| NFR3 | Epic 4, 5 | Location tracking updates <10s — Echo + Soketi WebSocket |
| NFR4 | Epic 2 | OCR processing <30s — async queue job with timeout |
| NFR5 | Epic 0 | 99% uptime — health checks + UptimeRobot |
| NFR6 | Epic 0, 4, 5 | 24/7 bot availability — queue workers + supervisor |
| NFR7 | Epic 0, 3 | Zero data loss — transaction-safe operations |
| NFR8 | Epic 0 | HTTPS/TLS encryption — Nginx config |
| NFR9 | Epic 0, 8 | RBAC enforcement — CheckRole middleware |
| NFR10 | Epic 3, 7, 9 | Audit trail — `AuditService` + `Auditable` trait |
| NFR11 | Epic 0, 1 | Passwordless auth — Telegram Login Widget |
| NFR12 | Epic 0, 4 | Telegram rate limit handling — RateLimitBot middleware |
| NFR13 | Epic 7 | QuickBooks valid import format — export service validation |
| NFR14 | Epic 2 | OCR fallback to manual — confidence routing + OPS entry |

---

## Stories

<!-- ============================================================ -->
<!-- EPIC 0: PROJECT FOUNDATION & ENVIRONMENT SETUP              -->
<!-- ============================================================ -->

## Epic 0: Project Foundation & Environment Setup

**Goal:** Development team has a working, deployable Laravel 12 environment with all tooling, authentication, RBAC, and observability infrastructure ready.

---

### Story 0.1: Install Laravel Boost and Initialize MCP Server

As a **Developer Agent**,
I want to install Laravel Boost and initialize the MCP Server,
So that all AI coding agents can connect and follow Boost guidelines from Day 1.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** Boost configuration, MCP server initialization
3. **Blade/Livewire:** N/A
4. **Pest Test:** Verify `boost:install` ran successfully, MCP server responds

**Acceptance Criteria:**

**Given** a fresh Laravel 12 project created via `composer create-project laravel/laravel pjl-connect`
**When** I run `composer require laravel/boost --dev` and `php artisan boost:install`
**Then** Laravel Boost is installed and the MCP Server is accessible
**And** `php artisan boost:skills` lists available skills
**And** the Boost MCP server is configured and responding

**Definition of Done:**
- [ ] Feature works: Boost installed, MCP server accessible
- [ ] Tests pass: Verification test confirms Boost presence
- [ ] Contextual logging: N/A (first task — no business logic yet)

---

### Story 0.2: Laravel 12 Scaffolding with TailAdmin Integration

As a **Developer Agent**,
I want the Laravel 12 project scaffolded with TailAdmin, Tailwind CSS 4, Alpine.js 3, and Livewire 3,
So that the Back-Office has a working dashboard shell with sidebar, header, and responsive layout.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** Vite config, Tailwind config with PJL brand colors
3. **Blade/Livewire:** `layouts/app.blade.php` (TailAdmin shell), `partials/sidebar.blade.php`, `partials/header.blade.php`, `partials/footer.blade.php`
4. **Pest Test:** Layout renders without errors, sidebar navigation links resolve

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** Laravel 12 with Boost installed (Story 0.1)
**When** TailAdmin template is integrated and Tailwind/Alpine/Livewire are configured
**Then** the Back-Office shell renders with Deep Teal (#1E5A6B) sidebar and Sky Blue (#5BC0DE) accents
**And** `npm run dev` compiles assets without errors
**And** the layout is responsive (sidebar collapses on mobile, hamburger menu appears)
**And** `tailwind.config.js` includes `deep-teal` and `sky-blue` custom colors

**Definition of Done:**
- [ ] Feature works: Dashboard shell renders in browser
- [ ] Tests pass: Layout rendering tests pass
- [ ] Contextual logging: N/A (no business logic)

---

### Story 0.3: Database Foundation and User Authentication

As an **OPS Staff member**,
I want to log in to the Back-Office using my Telegram account (passwordless),
So that I can access the system without remembering a password.

As a **System Admin**,
I want to log in via email/password with 2FA,
So that the highest-privilege account is secured independently of Telegram.

**Implementation Units:**
1. **Migration:** `create_users_table`, `create_system_configs_table` (with utf8mb4 charset D27)
2. **Model/Logic:** `User` model with `HasUuid` trait, dual auth guards (telegram + web), `UserRole` enum, Laravel Fortify + 2FA for SysAdmin
3. **Blade/Livewire:** `auth/telegram-login.blade.php`, `auth/system-admin-login.blade.php`
4. **Pest Test:** Telegram login flow, SysAdmin login + 2FA flow, guard isolation tests

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** the database is configured with `utf8mb4` charset and `utf8mb4_unicode_ci` collation
**When** I run `php artisan migrate`
**Then** the `users` and `system_configs` tables are created with correct column types (NVARCHAR for name fields per D27)
**And** `UserRole` enum defines all 7 roles: SADM, ADM, OPSM, OPS, BRK, ACCT, VIEW

**Given** a user with Telegram ID exists in the `users` table
**When** they click the Telegram Login Widget on the login page
**Then** the system verifies their Telegram identity and creates a session
**And** they are redirected to the dashboard

**Given** a System Admin (SADM) user
**When** they enter email/password + valid TOTP code
**Then** they are authenticated via the `web` guard with 2FA verification

**Definition of Done:**
- [ ] Feature works: Both login flows functional
- [ ] Tests pass: Auth guard tests, migration tests pass
- [ ] Contextual logging: Login attempts logged to `audit` channel with `auth_guard`, `login_method`, `telegram_user_id` context (D24)

---

### Story 0.4: RBAC Authorization Middleware

As an **Admin**,
I want role-based access control enforced on all Back-Office routes,
So that users can only access features permitted by their role.

**Implementation Units:**
1. **Migration:** `RoleSeeder` (seed 7 RBAC roles)
2. **Model/Logic:** `CheckRole` middleware, `UserRole` enum integration, route middleware groups
3. **Blade/Livewire:** Sidebar navigation conditionally renders menu items based on role
4. **Pest Test:** Route access tests for each role, unauthorized access returns 403

**Acceptance Criteria:**

**Given** the RBAC permission matrix from the PRD (Admin=Full, OPS Manager=Jobs+Booking+Reports, etc.)
**When** an OPS Staff user tries to access `/admin/users`
**Then** they receive a 403 Forbidden response
**And** the attempt is logged to the `audit` channel

**Given** roles are seeded via `php artisan db:seed --class=RoleSeeder`
**When** I check the sidebar navigation as an OPS Staff user
**Then** only Jobs and Booking menu items are visible
**And** Admin, Financial, and Compliance menus are hidden

**Definition of Done:**
- [ ] Feature works: RBAC enforced on all routes per permission matrix
- [ ] Tests pass: Role-based access tests for all 7 roles pass
- [ ] Contextual logging: Authorization failures logged with `user_id`, `user_role`, `attempted_route` context (D24)

---

### Story 0.5: Day 2 Operations Infrastructure

As a **System Admin**,
I want health checks, structured logging, and exception handling configured from Day 1,
So that the system is observable and debuggable from the first deployment.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `HealthController` (`/up` endpoint), `InjectLogContext` middleware, `DomainException` base class, `Handler.php` exception mapping, logging channels (`bot`, `ocr`, `audit`, `queue`)
3. **Blade/Livewire:** Error state components (`<x-error-state>`, `<x-empty-state>`), toast notification system
4. **Pest Test:** Health check endpoint returns 200, log context includes `request_id`, exception handler returns correct HTTP codes

**Acceptance Criteria:**

**Given** the application is running
**When** I request `GET /up`
**Then** I receive a JSON response with `status: "healthy"` and checks for database, Redis, storage, and queue

**Given** any web request is processed
**When** the `InjectLogContext` middleware runs
**Then** all log entries include `request_id`, `user_id`, `user_role`, `session_id`, `ip`, `url`, `method`
**And** the response header `X-Request-Id` is set

**Given** a `JobNotFoundException` is thrown
**When** the global exception handler catches it
**Then** JSON responses return `{success: false, error: {code: "JOB_NOT_FOUND", message: "We couldn't find that job..."}}` with HTTP 404
**And** Livewire/web requests flash the user-friendly message and redirect back

**Definition of Done:**
- [ ] Feature works: Health check, logging, and exception handling operational
- [ ] Tests pass: Health check, log context, and exception mapping tests pass
- [ ] Contextual logging: All 5 log channels configured (`web`, `bot`, `ocr`, `audit`, `queue`) with correct retention (D24)

---

### Story 0.6: CI/CD Pipeline Setup

As a **Developer Agent**,
I want a GitHub Actions pipeline that lints, tests, builds, and deploys on push to main,
So that code quality is enforced automatically and deployments are zero-touch.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `.github/workflows/deploy.yml`, `.env.testing`
3. **Blade/Livewire:** N/A
4. **Pest Test:** Pipeline runs Pint + Pest successfully

**Acceptance Criteria:**

**Given** the GitHub Actions workflow file exists at `.github/workflows/deploy.yml`
**When** a push to `main` occurs
**Then** the pipeline runs: lint (Pint) → test (Pest with MySQL + Redis services) → build (Vite) → deploy (SSH to Hostinger VPS)
**And** PRs run lint + test + build without deploying

**Definition of Done:**
- [ ] Feature works: Pipeline passes on a clean commit
- [ ] Tests pass: Pint + Pest run in CI
- [ ] Contextual logging: N/A (infrastructure)

---

<!-- ============================================================ -->
<!-- EPIC 1: CUSTOMER REGISTRATION & PROFILE MANAGEMENT          -->
<!-- ============================================================ -->

## Epic 1: Customer Registration & Profile Management

**Goal:** Customers can register via Telegram `/start`, get their profile auto-bound, and OPS can view/manage all customer profiles in the Back-Office.

---

### Story 1.1: Customer Registration via Telegram /start Command

As a **Customer**,
I want to register with PJL Connect by sending `/start` to the Telegram bot,
So that my Telegram identity is bound to a customer profile without needing a password.

**Implementation Units:**
1. **Migration:** `create_customers_table` (with NVARCHAR columns for Khmer support D27)
2. **Model/Logic:** `Customer` model with `HasUuid` + `SoftDeletesWithAudit` traits, `CustomerService`, `CustomerBot/Commands/StartCommand`
3. **Blade/Livewire:** N/A (Telegram-only)
4. **Pest Test:** `/start` command creates customer, duplicate `/start` returns existing profile, webhook signature verification

**Acceptance Criteria:**

**Given** a new Telegram user sends `/start` to @PJLConnectBot
**When** the webhook is received and signature is verified (D6)
**Then** a new `customers` record is created with `telegram_id`, `telegram_username`, and `contact_name` extracted from the Telegram payload
**And** the customer receives a welcome message with instructions
**And** a `uuid` is auto-generated (D4)

**Given** an existing customer sends `/start` again
**When** the system finds a matching `telegram_id`
**Then** the system returns a welcome-back message with their profile summary
**And** no duplicate record is created

**Definition of Done:**
- [ ] Feature works: Customer registration via `/start` creates profile
- [ ] Tests pass: Webhook tests with signed payloads, duplicate handling
- [ ] Contextual logging: Registration logged to `bot` channel with `telegram_user_id`, `customer_id` context (D24)

---

### Story 1.2: Customer Profile Management in Back-Office

As an **OPS Staff member**,
I want to create and edit customer profiles in the Back-Office,
So that I can manage customer information and link Telegram accounts.

**Implementation Units:**
1. **Migration:** N/A (uses `customers` table from Story 1.1)
2. **Model/Logic:** `CustomerProfileService` for CRUD operations
3. **Blade/Livewire:** `CustomerController`, Livewire customer list with search/filter, customer detail/edit form
4. **Pest Test:** CRUD operations, validation, RBAC (only OPS+ can manage)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an OPS user navigates to `/customers`
**When** the page loads
**Then** a data table displays all active customers with columns: Company Name, Contact Name, Telegram, Phone, Status
**And** the table supports search, filter by status, and pagination (25 per page per D12)

**Given** an OPS user clicks "Create Customer"
**When** they fill in company name, contact name, phone, and address
**Then** a new customer record is created with a generated UUID
**And** a success toast appears: "Customer created successfully"

**Given** an OPS user edits a customer's company_name with Khmer text (e.g., "ក្រុមហ៊ុន PJL")
**When** they save the form
**Then** the Khmer text is stored correctly (utf8mb4 D27) and displays properly

**Empty State:** "No customers found. Create your first customer!" with CTA button

**Definition of Done:**
- [ ] Feature works: Full CRUD for customer profiles
- [ ] Tests pass: Livewire component tests, validation tests, RBAC tests
- [ ] Contextual logging: Customer create/update/delete logged to `web` channel with `customer_id`, `action` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 2: BOOKING & DOCUMENT INTAKE                           -->
<!-- ============================================================ -->

## Epic 2: Booking & Document Intake

**Goal:** Customers create bookings via Telegram, upload documents, get OCR extraction, and receive Visual Receipt Cards. OPS views/creates bookings in Back-Office.

---

### Story 2.1: Booking Creation via Telegram /new Command

As a **Customer**,
I want to create a new booking by sending `/new` to the Telegram bot and selecting a service type,
So that I can submit a shipment request without calling or emailing.

**Implementation Units:**
1. **Migration:** `create_bookings_table` (with NVARCHAR for origin/destination/cargo D27)
2. **Model/Logic:** `Booking` model with `HasUuid` + `SoftDeletesWithAudit`, `BookingService`, `BookingValidationService`, `BookCommand`, `BookingFlowHandler`, `BookingStatus` enum
3. **Blade/Livewire:** N/A (Telegram-only)
4. **Pest Test:** `/new` command flow, service type selection, booking creation, reference number generation

**Acceptance Criteria:**

**Given** a registered customer sends `/new` to @PJLConnectBot
**When** the bot presents service type options (Truck, Sea, Air, Multi-leg) as inline buttons
**Then** the customer taps a service type
**And** the system creates a draft booking with `reference_number` in `BK-YYYY-NNNN` format (FR18)
**And** the booking status is set to `draft`

**Given** the customer is in the booking flow
**When** they provide origin and destination (typed or selected)
**Then** the booking record is updated with the provided details
**And** the system prompts for document upload

**Definition of Done:**
- [ ] Feature works: Complete booking flow via Telegram
- [ ] Tests pass: Booking creation, reference number uniqueness, flow handler tests
- [ ] Contextual logging: Booking created logged to `bot` channel with `booking_id`, `customer_id`, `service_type` context (D24)

---

### Story 2.2: Document Upload and OCR Processing Pipeline

As a **Customer**,
I want to upload booking documents in any format (PDF, Excel, Word, Image),
So that the system can automatically extract booking data without manual entry.

**Implementation Units:**
1. **Migration:** `create_documents_table` (with NVARCHAR for original_filename D27)
2. **Model/Logic:** `Document` model, `OcrExtractionService`, `DocumentUploadHandler`, Google Cloud Vision API integration, OCR queue job, `DocumentType` enum, `OcrStatus` enum
3. **Blade/Livewire:** N/A (Telegram bot handles upload)
4. **Pest Test:** File upload handling, OCR queue job dispatch, confidence scoring, fallback to manual

**Acceptance Criteria:**

**Given** a customer is in the booking flow and prompted for documents
**When** they upload a PDF, Excel, Word document, or Image via Telegram
**Then** the file is stored at `storage/app/documents/{job_id}/` (D14)
**And** an OCR queue job is dispatched to Google Cloud Vision API
**And** the `ocr_status` is set to `processing`

**Given** OCR processing completes within 30 seconds (NFR4)
**When** confidence score is >85%
**Then** extracted fields are auto-accepted and stored in `ocr_extracted_data` JSON
**And** `ocr_confidence_score` and `ocr_status: completed` are recorded

**Given** OCR processing fails or API is unavailable
**When** the fallback triggers (NFR14)
**Then** the system notifies the customer: "We couldn't read your document. OPS will review it manually."
**And** `ocr_status` is set to `failed`

**Definition of Done:**
- [ ] Feature works: Documents upload, OCR processes, confidence scores recorded
- [ ] Tests pass: Upload handler, OCR job, fallback logic tests
- [ ] Contextual logging: OCR processing logged to `ocr` channel with `document_id`, `file_type`, `confidence_score`, `duration_ms` context (D24)

---

### Story 2.3: OCR Confidence Routing and Customer Data Confirmation

As a **Customer**,
I want to confirm or correct OCR-extracted booking data,
So that I can ensure my booking details are accurate before submission.

**Implementation Units:**
1. **Migration:** N/A (uses existing tables)
2. **Model/Logic:** Confidence routing logic in `BookingService`, Telegram inline button handlers for confirm/correct
3. **Blade/Livewire:** N/A (Telegram-only)
4. **Pest Test:** Confidence thresholds (>85%, 70-85%, <70%), confirmation flow, correction flow

**Acceptance Criteria:**

**Given** OCR extraction is complete with confidence >85%
**When** the system presents extracted data to the customer via Telegram
**Then** fields are auto-accepted and displayed in a formatted message
**And** the customer can tap "Confirm" to accept or "Edit" to correct

**Given** OCR confidence is 70-85%
**When** the data is presented
**Then** fields are shown with a "Suggested" tag and the customer must confirm each field

**Given** OCR confidence is <70%
**When** the data is presented
**Then** fields are highlighted as "Needs Review" and the customer must manually input values

**Given** the customer confirms all data
**When** they tap "Confirm Booking"
**Then** the booking status changes from `draft` to `pending_review`
**And** the `BookingConfirmed` event is dispatched

**Definition of Done:**
- [ ] Feature works: Confidence routing displays correct UI for each threshold
- [ ] Tests pass: Threshold boundary tests, confirmation flow tests
- [ ] Contextual logging: Confirmation logged to `bot` channel with `booking_id`, `confidence_score`, `fields_corrected` context (D24)

---

### Story 2.4: Visual Receipt Card Generation

As a **Customer**,
I want to receive a Visual Receipt Card confirming my booking details,
So that I have a clear, professional confirmation of what I submitted.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `MessageFormatter` for Telegram receipt card generation
3. **Blade/Livewire:** N/A (Telegram formatted message)
4. **Pest Test:** Receipt card generation with all field types, edge cases (missing fields)

**Acceptance Criteria:**

**Given** a customer confirms their booking (Story 2.3)
**When** the booking status is `pending_review`
**Then** the system sends a Visual Receipt Card via Telegram containing: booking reference (BK-YYYY-NNNN), service type, origin, destination, cargo description, document count
**And** the card uses premium formatting (bold headers, emoji indicators, structured layout)

**Definition of Done:**
- [ ] Feature works: Receipt card sent after booking confirmation
- [ ] Tests pass: Card content generation tests
- [ ] Contextual logging: Receipt sent logged to `bot` channel with `booking_id`, `customer_id` context (D24)

---

### Story 2.5: Booking List and Filtering in Back-Office

As an **OPS Staff member**,
I want to view all bookings in a filterable, sortable list,
So that I can quickly find and manage customer bookings.

**Implementation Units:**
1. **Migration:** N/A (uses existing `bookings` table)
2. **Model/Logic:** `BookingController` with query scopes for filtering
3. **Blade/Livewire:** `BookingList` Livewire component with TailAdmin data table, filters (status, customer, date range, service type), pagination
4. **Pest Test:** Filter combinations, sort orders, pagination, RBAC access, empty state

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an OPS user navigates to `/bookings`
**When** the page loads
**Then** a data table shows bookings with columns: Reference #, Customer, Service Type, Status, Date, Actions
**And** default sort is by `created_at` descending
**And** pagination shows 25 items per page with page controls

**Given** an OPS user applies filters (status: "pending_review", service type: "truck")
**When** the filters are applied
**Then** only matching bookings are displayed
**And** a "Clear Filters" button is visible

**Empty State:** "No bookings found. Create your first booking!" with link to `/bookings/create`
**Loading State:** Skeleton table rows (8 rows) while data loads
**Error State:** "Failed to fetch bookings. [Retry]" banner above table

**Definition of Done:**
- [ ] Feature works: Booking list with filters, sort, pagination
- [ ] Tests pass: Livewire component tests, filter tests, empty state tests
- [ ] Contextual logging: Booking list access logged to `web` channel with `filters_applied`, `result_count` context (D24)

---

### Story 2.6: OPS Manual Booking Creation

As an **OPS Staff member**,
I want to manually create bookings on behalf of customers in the Back-Office,
So that I can process bookings received via phone or email.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `BookingService.createManual()` with `submitted_via: web` and `submitted_by_user_id`
3. **Blade/Livewire:** `BookingWizard` Livewire component with multi-step form (customer select → service type → details → confirm)
4. **Pest Test:** Manual booking creation, customer selection, validation, RBAC

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an OPS user clicks "Create Booking" on the bookings page
**When** they complete the multi-step wizard (select customer, choose service type, enter details)
**Then** a booking is created with `submitted_via: web` and `submitted_by_user_id` set
**And** a reference number in `BK-YYYY-NNNN` format is generated
**And** a success toast: "Booking BK-2026-0001 created successfully"

**Definition of Done:**
- [ ] Feature works: Manual booking creation via wizard
- [ ] Tests pass: Wizard flow tests, validation tests
- [ ] Contextual logging: Manual booking logged to `web` channel with `booking_id`, `created_by_user_id`, `customer_id` context (D24)

---

### Story 2.7: Document Attachment to Bookings and Jobs

As an **OPS Staff member**,
I want to attach shipping documents to any booking or job from the Back-Office,
So that all relevant paperwork is centrally stored and accessible.

**Implementation Units:**
1. **Migration:** N/A (uses existing `documents` table)
2. **Model/Logic:** `JobDocumentService` for file upload, validation (type, size), storage to `storage/app/documents/{job_id}/`
3. **Blade/Livewire:** Document upload component with drag-drop, file type icons, progress indicator
4. **Pest Test:** Upload validation, storage path, multiple file upload, RBAC

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an OPS user is viewing a booking or job detail
**When** they drag-and-drop or select files to upload
**Then** files are validated (PDF, Excel, Word, Image accepted), stored at `storage/app/documents/{job_id}/`
**And** `documents` records are created with `original_filename`, `mime_type`, `file_size_bytes`, `storage_path`
**And** a "Document uploaded" success toast appears

**Definition of Done:**
- [ ] Feature works: Documents upload and attach to booking/job
- [ ] Tests pass: Upload validation, storage tests
- [ ] Contextual logging: Document upload logged to `web` channel with `document_id`, `job_id`, `file_type`, `file_size_bytes` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 3: JOB MANAGEMENT & OPS COMMAND CENTER                 -->
<!-- ============================================================ -->

## Epic 3: Job Management & OPS Command Center

**Goal:** OPS manages the full job lifecycle: Kanban board, carrier assignment, comments, document handling, and status management.

---

### Story 3.1: Booking-to-Job Conversion and Reference Numbers

As an **OPS Staff member**,
I want confirmed bookings to automatically convert into jobs with unique reference numbers,
So that each shipment is tracked as a formal job in the system.

**Implementation Units:**
1. **Migration:** `create_jobs_table`, `create_shipment_legs_table` (with NVARCHAR columns per D27)
2. **Model/Logic:** `Job` model with `HasUuid` + `SoftDeletesWithAudit`, `ShipmentLeg` model, `JobService.createFromBooking()`, `JobStatus` enum with state machine, `JobPriority` enum
3. **Blade/Livewire:** N/A (backend conversion, displayed on Kanban in Story 3.2)
4. **Pest Test:** Conversion creates job with correct reference, booking status updates to `converted`, event dispatched

**Acceptance Criteria:**

**Given** a booking with status `confirmed`
**When** OPS clicks "Convert to Job" or the system auto-converts
**Then** a `jobs` record is created with `reference_number` in `PJL-YYYY-NNNN` format (FR18)
**And** the booking `status` changes to `converted` and `job_id` is linked
**And** `JobCreated` event is dispatched
**And** shipment legs are created based on service type (single leg for truck/air, multiple for multi-leg)

**Given** a multi-leg booking (sea + truck)
**When** converted to a job
**Then** `shipment_legs` records are created with correct `leg_order`, `type`, `origin`, `destination`

**Definition of Done:**
- [ ] Feature works: Booking converts to job with reference number and legs
- [ ] Tests pass: Conversion, reference uniqueness, event dispatch, state machine tests
- [ ] Contextual logging: Conversion logged to `web` channel with `job_id`, `booking_id`, `reference_number`, `legs_count` context (D24)

---

### Story 3.2: Job Kanban Board

As an **OPS Staff member**,
I want to view all jobs on a Kanban board grouped by status with real-time drag-drop,
So that I can manage the workflow visually and move jobs through stages.

**Implementation Units:**
1. **Migration:** N/A (uses existing `jobs` table)
2. **Model/Logic:** `JobKanbanService` for column data, status transition validation, optimistic update logic
3. **Blade/Livewire:** `KanbanBoard` Livewire component with Filament Kanban, `JobCard` component (reference #, customer, carrier, priority badge, status pill), drag-drop with optimistic UI + rollback
4. **Pest Test:** Kanban renders columns, drag updates status, invalid transitions rejected, real-time polling

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an OPS user navigates to `/jobs`
**When** the Kanban board loads
**Then** columns display for each `JobStatus`: New, Pending Assignment, Carrier Notified, Accepted, Driver Assigned, In Transit, At Pickup, At Destination, Delivered, Completed
**And** each `JobCard` shows: reference number (monospace), customer name, carrier, priority badge, status pill
**And** the board loads within 3 seconds (NFR1)

**Given** OPS drags a job card from "New" to "Pending Assignment"
**When** the card is dropped
**Then** the UI updates optimistically (card moves immediately)
**And** Livewire confirms the change server-side
**And** `JobStatusUpdated` event is dispatched

**Given** OPS tries to drag a job from "New" directly to "Delivered" (invalid transition)
**When** the drop is attempted
**Then** the card snaps back to original column
**And** an error toast: "This job can't be moved to that status right now" (exception D24)

**Empty State:** "No jobs yet. Create your first booking!" → Link to `/bookings/create`
**Loading State:** Skeleton columns with ghost cards (3 per column)

**Definition of Done:**
- [ ] Feature works: Kanban board with drag-drop, real-time updates
- [ ] Tests pass: Livewire component tests, status transition tests, optimistic UI rollback tests
- [ ] Contextual logging: Status changes logged to `web` channel with `job_id`, `old_status`, `new_status`, `changed_by_user_id` context (D24)

---

### Story 3.3: Carrier Assignment to Jobs

As an **OPS Staff member**,
I want to assign a carrier to a job from the Kanban board or job detail,
So that the carrier is notified and can begin fulfillment.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `JobAssignmentService.assignCarrier()`, carrier availability check, `JobAssigned` event dispatch
3. **Blade/Livewire:** Carrier Assignment Modal (search, filter by route, show rates from `rate_cards`), inline assign button on job card
4. **Pest Test:** Assignment flow, duplicate assignment prevention, event dispatch, RBAC

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** OPS opens the Carrier Assignment Modal from a job card
**When** they search for carriers and see available options with rates
**Then** carriers are listed with: company name, rate (from `rate_cards`), availability status
**And** clicking "Assign" sets `carrier_id` on the job and changes status to `carrier_notified`

**Given** a carrier is already assigned to a job
**When** OPS tries to assign another carrier
**Then** the system shows "This job has already been assigned to a carrier" (409 Conflict, D24)

**Empty State in Modal:** "No carriers available for this route."

**Definition of Done:**
- [ ] Feature works: Carrier assignment with rate display
- [ ] Tests pass: Assignment flow, conflict detection, event dispatch tests
- [ ] Contextual logging: Assignment logged to `web` channel with `job_id`, `carrier_id`, `assigned_by_user_id` context (D24)

---

### Story 3.4: Job Status Management and Manual Override

As an **OPS Staff member**,
I want to manually override a job's status (including cancel and on-hold),
So that I can handle exceptions and corrections in the workflow.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `JobStatusService.override()` with reason field, state machine bypass for admin overrides (FR16)
3. **Blade/Livewire:** Status override dropdown in job detail slide-over, confirmation modal for destructive actions (cancel)
4. **Pest Test:** Override with reason, cancel/on-hold from any status, audit trail, RBAC (OPS Manager+ only)

**Acceptance Criteria:**

**Given** OPS Manager views a job detail
**When** they select "Override Status" and choose "Cancelled" with a reason
**Then** the job status changes to `cancelled` regardless of current status (FR16)
**And** a confirmation modal appears: "Are you sure? This cannot be undone."
**And** the override is recorded in the audit trail with the reason

**Given** any job in any status
**When** OPS Manager sets it to "On Hold"
**Then** the job status changes to `on_hold` and a reason is required
**And** the job card shows a yellow "On Hold" badge on the Kanban

**Definition of Done:**
- [ ] Feature works: Manual override for cancel/on-hold from any status
- [ ] Tests pass: Override flow, state machine bypass, audit trail, RBAC tests
- [ ] Contextual logging: Override logged to `web` + `audit` channels with `job_id`, `old_status`, `new_status`, `override_reason`, `overridden_by_user_id` context (D24)

---

### Story 3.5: Job Comments and Notes

As an **OPS Staff member**,
I want to add comments and notes to a job,
So that the team can collaborate and track information about the shipment.

**Implementation Units:**
1. **Migration:** N/A (uses `notes` field on `jobs` table, or separate `job_comments` table if needed)
2. **Model/Logic:** `JobService.addComment()` with timestamp and user attribution
3. **Blade/Livewire:** Comments section in job detail slide-over with real-time updates
4. **Pest Test:** Add comment, view history, Khmer text support, RBAC

**Acceptance Criteria:**

**Given** OPS views a job detail slide-over
**When** they type a comment in the notes field and click "Add Note"
**Then** the comment is saved with timestamp, user name, and displayed in reverse chronological order
**And** Khmer text (D27) is stored and rendered correctly

**Definition of Done:**
- [ ] Feature works: Comments added and displayed in job detail
- [ ] Tests pass: Comment create, display order, Khmer text tests
- [ ] Contextual logging: Comment logged to `web` channel with `job_id`, `comment_by_user_id` context (D24)

---

### Story 3.6: OPS Assignment (Auto and Manual)

As an **OPS Manager**,
I want bookings to be auto-assigned to available OPS staff or manually assigned,
So that workload is distributed evenly and every booking has an owner.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `JobAssignmentService.autoAssignOps()` (round-robin or lowest active count), `JobAssignmentService.manualAssignOps()`, assignment rules in `SystemConfig`
3. **Blade/Livewire:** OPS assignment dropdown in booking/job detail, assignment indicator on Kanban cards
4. **Pest Test:** Auto-assign distributes evenly, manual assign works, reassignment logged

**Acceptance Criteria:**

**Given** a new booking arrives and auto-assignment is enabled (FR19)
**When** the system runs assignment logic
**Then** the booking is assigned to the OPS staff with the lowest active job count
**And** the assigned OPS sees the booking in their filtered view

**Given** OPS Manager wants to manually assign a booking (FR20)
**When** they select an OPS staff from the dropdown
**Then** `assigned_ops_id` is set and the assignment is reflected on the Kanban card

**Definition of Done:**
- [ ] Feature works: Auto and manual OPS assignment
- [ ] Tests pass: Round-robin logic, manual override, RBAC tests
- [ ] Contextual logging: Assignment logged to `web` channel with `job_id`, `assigned_ops_id`, `assignment_type` (auto/manual) context (D24)

---

### Story 3.7: Document Print, Preview, and Annotation

As an **OPS Staff member**,
I want to preview documents in-browser, print all documents for a job, and annotate PDFs,
So that I can review paperwork digitally and mark up documents as needed.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `JobDocumentService.getPreviewUrl()`, `JobDocumentService.getBatchPrintUrl()`, PDF annotation storage in `documents.annotations` JSON column (FR23)
3. **Blade/Livewire:** Document preview modal (PDF.js for PDFs, native for images), print all button, annotation toolbar (red text, circles, rectangles)
4. **Pest Test:** Preview renders, print batch works, annotations saved/loaded, RBAC

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** OPS views a job detail and clicks on a document
**When** the document preview modal opens
**Then** PDFs render via PDF.js, images display natively, Excel/Word show download buttons
**And** all formats are viewable in-browser (FR22)

**Given** OPS clicks "Print All Documents" on a job
**When** the print batch is generated
**Then** all attached documents are combined and sent to the print dialog (FR21)

**Given** OPS enables PDF annotation mode (FR23)
**When** they draw circles, rectangles, or add red text on a PDF
**Then** annotations are saved in `documents.annotations` JSON and persist across sessions

**Definition of Done:**
- [ ] Feature works: Preview, batch print, PDF annotation
- [ ] Tests pass: Preview renders, annotation save/load, print batch tests
- [ ] Contextual logging: Annotation logged to `web` channel with `document_id`, `annotation_type`, `annotated_by_user_id` context (D24)

---

### Story 3.8: Booking Summary Cover Page Print

As an **OPS Staff member**,
I want to print a booking summary cover page,
So that I can attach a standardized cover sheet to physical paperwork.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `BookingPrintService.generateCoverPage()` with booking and job details
3. **Blade/Livewire:** Cover page Blade template with print-optimized CSS (`@media print`), print button on booking detail
4. **Pest Test:** Cover page generates with correct data, print CSS applied

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** OPS views a booking detail
**When** they click "Print Cover Page" (FR24)
**Then** a formatted cover page opens in a new tab with: booking reference, job reference, customer info, service type, origin/destination, cargo summary, document list
**And** the page is print-optimized with PJL branding

**Definition of Done:**
- [ ] Feature works: Cover page generates and prints correctly
- [ ] Tests pass: Content generation tests
- [ ] Contextual logging: Print action logged to `web` channel with `booking_id`, `job_id` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 4: CARRIER & DRIVER OPERATIONS                         -->
<!-- ============================================================ -->

## Epic 4: Carrier & Driver Operations

**Goal:** Carriers receive job notifications via Telegram, accept/decline, Nag-Bot follows up, OPS gets escalation, and drivers share live GPS.

---

### Story 4.1: Carrier Registration and Telegram Bot Setup

As an **OPS Staff member**,
I want to register carriers in the Back-Office and bind their Telegram accounts,
So that they can receive job notifications automatically.

**Implementation Units:**
1. **Migration:** `create_carriers_table`, `create_drivers_table` (with NVARCHAR for Khmer names D27)
2. **Model/Logic:** `Carrier` model with `HasUuid` + `SoftDeletesWithAudit`, `Driver` model, `CarrierService`, carrier QR enrollment token generation
3. **Blade/Livewire:** Carrier CRUD in Back-Office (list, create, edit), QR token generation button
4. **Pest Test:** Carrier CRUD, QR token uniqueness, Telegram binding, RBAC

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** OPS navigates to `/carriers` and clicks "Add Carrier"
**When** they enter carrier details (company name, contact, phone, license, insurance)
**Then** a carrier record is created with generated UUID and QR enrollment token
**And** the carrier can scan the QR to bind their Telegram account

**Given** a carrier sends `/start` to @PJLCarrierBot with their QR token
**When** the system validates the token
**Then** the carrier's `telegram_id` is bound to the carrier record
**And** `is_approved` remains `false` until OPS approves

**Definition of Done:**
- [ ] Feature works: Carrier registration, QR binding, driver management
- [ ] Tests pass: CRUD, QR token, Telegram binding tests
- [ ] Contextual logging: Carrier registration logged to `web` channel with `carrier_id`, `action` context (D24)

---

### Story 4.2: Job Notification and Accept/Decline via Carrier Bot

As a **Carrier**,
I want to receive job notifications via Telegram and accept or decline with inline buttons,
So that I can respond to job offers quickly and efficiently.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `CarrierNotificationService`, Telegram inline keyboard handlers for accept/decline, `CarrierJobAccepted` and `CarrierJobDeclined` events
3. **Blade/Livewire:** N/A (Telegram-only)
4. **Pest Test:** Notification dispatch, accept/decline handling, event dispatch, Telegram rate limits (NFR12)

**Acceptance Criteria:**

**Given** a job is assigned to a carrier (Story 3.3)
**When** the `JobAssigned` event fires
**Then** the carrier receives a Telegram message with job details (reference, pickup, destination, cargo)
**And** inline buttons: "✅ Accept" and "❌ Decline"

**Given** the carrier taps "Accept"
**When** the callback is processed
**Then** the job status changes to `carrier_accepted`
**And** OPS receives a notification on the Kanban board

**Given** the carrier taps "Decline"
**When** the callback is processed
**Then** the carrier assignment is cleared, status reverts to `pending_assignment`
**And** OPS is notified to re-assign

**Definition of Done:**
- [ ] Feature works: Carrier receives notifications and can accept/decline
- [ ] Tests pass: Notification, accept/decline flows, event dispatch tests
- [ ] Contextual logging: Accept/decline logged to `bot` channel with `job_id`, `carrier_id`, `decision`, `response_time_ms` context (D24)

---

### Story 4.3: Nag-Bot and OPS Escalation

As an **OPS Staff member**,
I want the system to automatically follow up with carriers who haven't responded and escalate to me if they remain silent,
So that no job assignment falls through the cracks.

**Implementation Units:**
1. **Migration:** N/A (uses `nag_count`, `nag_last_sent_at` on `jobs` table)
2. **Model/Logic:** `NagBotService` (scheduled job), configurable interval via `system_configs`, escalation threshold, `CarrierEscalation` event
3. **Blade/Livewire:** Exception card on OPS dashboard for escalated carriers
4. **Pest Test:** Nag intervals, escalation threshold, OPS notification, config override

**Acceptance Criteria:**

**Given** a carrier hasn't responded to a job notification within the configured interval (default: 30 min per FR27)
**When** the Nag-Bot scheduler runs
**Then** a follow-up message is sent to the carrier via Telegram
**And** `nag_count` is incremented and `nag_last_sent_at` is updated

**Given** `nag_count` reaches the escalation threshold (default: 3) and carrier still hasn't responded (FR28)
**When** the Nag-Bot detects the threshold
**Then** the `CarrierEscalation` event fires
**And** OPS receives an exception card on the dashboard with: "Carrier [name] unresponsive — [View Job]"

**Definition of Done:**
- [ ] Feature works: Nag-Bot sends follow-ups, escalates to OPS
- [ ] Tests pass: Interval logic, escalation threshold, event dispatch tests
- [ ] Contextual logging: Nag sent/escalation logged to `bot` channel with `job_id`, `carrier_id`, `nag_count`, `escalation_triggered` context (D24)

---

### Story 4.4: Driver Telegram Bot and Live Location Sharing

As a **Driver**,
I want to share my live GPS location via Telegram,
So that OPS and customers can track my shipment in real time.

**Implementation Units:**
1. **Migration:** `create_location_pings_table` (immutable, append-only)
2. **Model/Logic:** `LocationPing` model, `DriverBot/Handlers/LocationHandler`, GPS coordinate storage, `DriverLocationUpdated` event, `PingSource` enum
3. **Blade/Livewire:** N/A (Telegram-only; map view is Epic 5)
4. **Pest Test:** Location ping storage, accuracy handling, rate limiting, manual pin entry fallback

**Acceptance Criteria:**

**Given** a driver is assigned to an active job
**When** they share live location via Telegram's location sharing feature (FR29)
**Then** `location_pings` records are created with `latitude`, `longitude`, `accuracy_meters`, `speed_kmh`, `heading`, `source: telegram_live`, `pinged_at`
**And** `DriverLocationUpdated` event is dispatched for real-time map updates (FR30)

**Given** the driver's GPS is unavailable
**When** they tap "Send Location Manually" (manual pin)
**Then** they can send a one-time location pin with `source: manual_pin`

**Given** location updates arrive within 10 seconds (NFR3)
**When** pings are stored
**Then** no `updated_at` column exists (immutable table) and soft deletes are not applied

**Definition of Done:**
- [ ] Feature works: Live location pings stored from Telegram
- [ ] Tests pass: Ping storage, manual pin, coordinate validation tests
- [ ] Contextual logging: Location pings logged to `bot` channel with `driver_id`, `job_id`, `ping_source`, `accuracy_meters` context (D24)

---

### Story 4.5: Real-Time Driver Map View

As an **OPS Staff member**,
I want to see all active drivers on a live map with clickable markers,
So that I can monitor shipment progress geographically.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `DriverMapService` for active driver queries with latest ping
3. **Blade/Livewire:** `LiveMap` Livewire component with Leaflet.js, driver markers with info popups, Laravel Echo + Soketi for real-time updates
4. **Pest Test:** Map data endpoint, real-time update handling, empty state

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** OPS navigates to `/tracking/map`
**When** the map loads
**Then** a Leaflet.js map centered on Cambodia shows driver markers at their latest location (FR31)
**And** clicking a marker shows: driver name, carrier, current job reference, last ping time, speed

**Given** a driver sends a new location ping
**When** the `DriverLocationUpdated` event broadcasts via Soketi
**Then** the marker moves on the map in real-time without page refresh

**Empty State:** "No active drivers on map." (empty map with Cambodia region outline)

**Definition of Done:**
- [ ] Feature works: Live map with driver markers and real-time updates
- [ ] Tests pass: Map data endpoint, WebSocket broadcast tests
- [ ] Contextual logging: Map access logged to `web` channel with `active_drivers_count` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 5: TRACKING & LIVE MONITORING                          -->
<!-- ============================================================ -->

## Epic 5: Tracking & Live Monitoring

**Goal:** Customers track via `/track`, OPS monitors live GPS, silence escalation alerts fire, and geofences auto-trigger events.

---

### Story 5.1: Customer Shipment Tracking via /track Command

As a **Customer**,
I want to track my shipment status by sending `/track` to the Telegram bot,
So that I can get real-time updates without calling OPS.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `TrackingService.getCustomerTrackingData()`, `CustomerBot/Commands/TrackCommand`
3. **Blade/Livewire:** N/A (Telegram-only)
4. **Pest Test:** Track command returns correct status, handles no active shipments, handles multiple shipments

**Acceptance Criteria:**

**Given** a customer sends `/track` to @PJLConnectBot (FR32)
**When** they have one active shipment
**Then** the bot replies with: status pill, current location (if tracked), last milestone, ETA, driver/carrier info

**Given** a customer has multiple active shipments
**When** they send `/track`
**Then** a list of shipments is shown with reference numbers as inline buttons
**And** tapping a reference shows full tracking detail

**Given** a customer has no active shipments
**When** they send `/track`
**Then** the bot replies: "No active shipments found."

**Definition of Done:**
- [ ] Feature works: `/track` returns shipment status
- [ ] Tests pass: Track command with single/multiple/no shipments
- [ ] Contextual logging: Track request logged to `bot` channel with `customer_id`, `shipment_count` context (D24)

---

### Story 5.2: GPS Blackout Detection and Silence Escalation

As an **OPS Staff member**,
I want to be alerted when a driver stops sharing location for too long,
So that I can investigate potential issues with the delivery.

**Implementation Units:**
1. **Migration:** N/A (uses `silence_escalated_at` on `jobs` table)
2. **Model/Logic:** `SilenceDetectionService` (scheduled job, checks last ping timestamp), configurable threshold via `system_configs`, `SilenceEscalation` event
3. **Blade/Livewire:** Exception card on OPS dashboard for silent drivers
4. **Pest Test:** Silence detection intervals, escalation trigger, false positive prevention (job complete = no alert)

**Acceptance Criteria:**

**Given** a driver's last location ping is older than the configured silence threshold (default: 30 min per FR33)
**When** the silence detection scheduler runs
**Then** the system detects the blackout and fires `SilenceEscalation` event (FR34)
**And** OPS receives an exception card: "⚠️ Driver [name] silent for [duration] — [View Job] [Call Driver]"

**Given** the driver resumes location sharing after a blackout
**When** pings resume
**Then** the exception card auto-resolves and shows "✅ Driver [name] back online"

**Given** a job is in `completed` or `cancelled` status
**When** silence detection runs
**Then** no alert is generated (false positive prevention)

**Definition of Done:**
- [ ] Feature works: Silence detection and escalation alerts
- [ ] Tests pass: Detection interval, escalation trigger, false positive prevention tests
- [ ] Contextual logging: Silence detection logged to `web` channel with `driver_id`, `job_id`, `last_ping_age_minutes`, `escalation_triggered` context (D24)

---

### Story 5.3: Manual Vessel and Flight Tracking Entry

As an **OPS Staff member**,
I want to manually enter vessel/flight tracking info for sea and air shipments,
So that customers can see shipment progress even when GPS isn't applicable.

**Implementation Units:**
1. **Migration:** N/A (uses `shipment_legs` table fields: `vessel_name`, `voyage_number`, `flight_number`, `etd`, `eta`, `atd`, `ata`, `tracking_info`)
2. **Model/Logic:** `ShipmentLegService.updateTracking()` with validation
3. **Blade/Livewire:** Tracking info form in job detail slide-over, per-leg editing for multi-leg shipments
4. **Pest Test:** Update tracking info, validation, multi-leg scenarios

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** OPS views a job detail with a sea shipment leg
**When** they enter vessel name, voyage number, ETD, ETA (FR35)
**Then** the shipment leg record is updated and displayed on the job timeline
**And** actual departure/arrival timestamps are recorded when entered

**Definition of Done:**
- [ ] Feature works: Manual tracking entry for vessel/flight legs
- [ ] Tests pass: Update, validation, multi-leg tests
- [ ] Contextual logging: Tracking update logged to `web` channel with `job_id`, `leg_id`, `updated_fields` context (D24)

---

### Story 5.4: ETA Calculation

As a **Customer**,
I want to see an estimated time of arrival for my shipment,
So that I can plan my receiving schedule.

**Implementation Units:**
1. **Migration:** N/A (uses `jobs.eta` field)
2. **Model/Logic:** `EtaCalculationService` using route distance + speed (truck), or leg ETA (sea/air), scheduled recalculation
3. **Blade/Livewire:** ETA display on job detail and tracking views
4. **Pest Test:** ETA calc for truck (GPS-based), sea/air (leg ETAs), edge cases

**Acceptance Criteria:**

**Given** a truck shipment with active GPS tracking (FR36)
**When** ETA is calculated
**Then** it uses current position, destination coordinates, and average speed to estimate arrival
**And** ETA updates dynamically as new location pings arrive

**Given** a sea/air shipment with manual tracking
**When** ETA is calculated
**Then** it uses the leg's `eta` field as the primary estimate

**Definition of Done:**
- [ ] Feature works: ETA calculated and displayed
- [ ] Tests pass: Calculation logic for truck/sea/air modes
- [ ] Contextual logging: ETA calculation logged to `web` channel with `job_id`, `eta_value`, `calculation_method` context (D24)

---

### Story 5.5: Geofence Management and Auto-Trigger Events

As a **System Admin**,
I want to define geofences around key locations and auto-trigger arrival/departure events,
So that job status updates automatically when a driver enters/exits a zone.

**Implementation Units:**
1. **Migration:** `create_geofences_table`
2. **Model/Logic:** `Geofence` model, `GeofenceService.checkProximity()`, geofence matching on location pings, `GeofenceTriggered` event, `GeofenceTrigger` enum
3. **Blade/Livewire:** Geofence admin CRUD in settings, geofence circles on the live map
4. **Pest Test:** Proximity calculation, trigger types (arrived/departed), auto status update, admin CRUD

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** a geofence is defined with center (lat/lng) and radius in meters (FR37)
**When** a driver's location ping falls within the geofence radius
**Then** a `GeofenceTriggered` event fires with trigger type (arrived/departed)
**And** the job's `pickup_actual_at` or `delivery_actual_at` is auto-set
**And** the job status transitions automatically (e.g., → `at_pickup` or `at_destination`)

**Given** an admin navigates to `/admin/geofences`
**When** they create a geofence with name, lat/lng, radius, and trigger type
**Then** the geofence is saved and visible as a circle on the live map

**Definition of Done:**
- [ ] Feature works: Geofences trigger events, auto-update job status
- [ ] Tests pass: Proximity calculation, trigger logic, admin CRUD tests
- [ ] Contextual logging: Geofence trigger logged to `web` channel with `geofence_id`, `driver_id`, `job_id`, `trigger_type`, `distance_meters` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 6: DOCUMENT & CUSTOMS COMPLIANCE                       -->
<!-- ============================================================ -->

## Epic 6: Document & Customs Compliance

**Goal:** Brokers use Copy-Paste Magic for ASYCUDA, review SAD data, and the system auto-tracks document deadlines.

---

### Story 6.1: Job Document Viewer

As a **Broker**,
I want to view all documents attached to a job in one place,
So that I can review shipment paperwork for customs preparation.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `DocumentViewerService` for document listing per job with type grouping
3. **Blade/Livewire:** Document viewer panel in job detail with type tabs (Invoices, B/L, AWB, etc.), preview thumbnails
4. **Pest Test:** Document listing, type filtering, RBAC (Broker+ access)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** a Broker navigates to a job detail (FR38)
**When** the Documents tab is selected
**Then** all attached documents are displayed grouped by `document_type` with thumbnails and metadata (filename, size, uploaded date)
**And** clicking a document opens the preview modal (from Story 3.7)

**Definition of Done:**
- [ ] Feature works: Document viewer displays all job documents by type
- [ ] Tests pass: Listing, type grouping, RBAC tests
- [ ] Contextual logging: Document view logged to `web` channel with `job_id`, `document_count` context (D24)

---

### Story 6.2: ASYCUDA Copy-Paste Magic

As a **Broker**,
I want to generate pre-formatted ASYCUDA data blocks and copy them to clipboard with one click,
So that I can enter customs declaration data into ASYCUDA efficiently without retyping.

**Implementation Units:**
1. **Migration:** `create_sad_declarations_table`
2. **Model/Logic:** `SadDeclaration` model with `HasUuid`, `AsycudaService.generateBlocks()` from job/booking data, clipboard API integration
3. **Blade/Livewire:** `AsycudaWorkbench` Livewire component, Copy-Paste Magic Block (`<x-copy-block>`) with one-click copy, success toast on copy
4. **Pest Test:** Block generation, copy functionality, edge cases (missing data fields)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** a Broker navigates to the ASYCUDA view for a job (FR39)
**When** they click "Generate ASYCUDA Blocks"
**Then** pre-formatted data blocks are generated from job data (consignee, HS codes, quantities, values)
**And** each block is displayed in a monospace `<x-copy-block>` component with a "📋 Copy" button

**Given** a Broker clicks the "Copy" button on a block
**When** the clipboard API is invoked
**Then** the formatted text is copied to clipboard
**And** a success toast appears: "✅ Copied to clipboard!"
**And** the button briefly shows a checkmark animation

**Definition of Done:**
- [ ] Feature works: ASYCUDA blocks generated and copyable
- [ ] Tests pass: Block generation, copy integration tests
- [ ] Contextual logging: Block generation logged to `web` channel with `job_id`, `sad_id`, `blocks_count` context (D24)

---

### Story 6.3: SAD Review Workbench

As a **Broker**,
I want to review and edit SAD declaration data before submission,
So that I can ensure accuracy of customs declarations.

**Implementation Units:**
1. **Migration:** N/A (uses `sad_declarations` table from Story 6.2)
2. **Model/Logic:** `SadReviewService` for validation and status tracking, `CustomsStatus` enum
3. **Blade/Livewire:** SAD review form with editable fields (consignee data, HS codes, item data), status tracking, declaration number entry after submission
4. **Pest Test:** CRUD, validation, status transitions, RBAC (Broker+ only)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** a Broker opens the SAD review for a job (FR40)
**When** they review the pre-populated fields
**Then** they can edit consignee data, HS codes, and item lines
**And** validation errors are shown inline (red borders per UX spec)

**Given** a Broker submits the SAD to customs
**When** they enter the ASYCUDA declaration number and mark status
**Then** `customs_status` updates (submitted → green/yellow/red lane)
**And** `submitted_at` timestamp is recorded

**Definition of Done:**
- [ ] Feature works: SAD review with edit, validate, submit flow
- [ ] Tests pass: CRUD, validation, status transition tests
- [ ] Contextual logging: SAD review logged to `web` channel with `sad_id`, `job_id`, `customs_status`, `reviewed_by_user_id` context (D24)

---

### Story 6.4: Document Deadline Tracking and Customer Reminders

As an **OPS Staff member**,
I want the system to track document submission deadlines and send reminders to customers,
So that no customs deadline is missed.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `DeadlineService` (calculates deadlines from shipment leg ETD per FR41), scheduled job for reminder checks, `DocumentDeadlineApproaching` event
3. **Blade/Livewire:** Deadline tracker widget on OPS dashboard, deadline indicators on job cards
4. **Pest Test:** Deadline calculation from ETD, reminder trigger, customer notification, edge cases

**Acceptance Criteria:**

**Given** a job has a shipment leg with an ETD set (FR41)
**When** the deadline calculation runs
**Then** document submission deadlines are computed (configured days before ETD)
**And** upcoming deadlines appear on the OPS dashboard

**Given** a deadline is approaching and documents are missing (FR42)
**When** the reminder scheduler runs
**Then** the customer receives a Telegram message: "📋 Documents needed for [job reference] — deadline: [date]"
**And** OPS sees an exception card for the approaching deadline

**Definition of Done:**
- [ ] Feature works: Deadlines calculated, reminders sent to customers
- [ ] Tests pass: Deadline calculation, reminder trigger, notification tests
- [ ] Contextual logging: Reminder sent logged to `bot` channel with `job_id`, `customer_id`, `deadline_date`, `days_remaining` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 7: FINANCIAL & REPORTING                               -->
<!-- ============================================================ -->

## Epic 7: Financial & Reporting

**Goal:** OPS generates invoices, Accounting exports to QuickBooks, Management sees KPI dashboards, and profit is auto-calculated.

---

### Story 7.1: Invoice Generation

As an **OPS Staff member**,
I want to generate invoices for completed jobs with auto-populated line items,
So that customers are billed accurately and promptly.

**Implementation Units:**
1. **Migration:** `create_invoices_table`, `create_invoice_line_items_table`
2. **Model/Logic:** `Invoice` model with `HasUuid` + `SoftDeletesWithAudit`, `InvoiceLineItem` model, `InvoiceService.generateFromJob()` (uses rate cards to auto-populate), `InvoiceStatus` enum, invoice number generation (`INV-YYYY-NNNN`)
3. **Blade/Livewire:** Invoice creation form, invoice preview/edit, PDF generation, send to customer
4. **Pest Test:** Invoice generation, line item calculation (cents), status transitions, PDF output

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** OPS clicks "Generate Invoice" on a completed job (FR43)
**When** the invoice form opens
**Then** line items are auto-populated from the job's customer rate card and service details
**And** subtotal, tax, and total are calculated in cents (USD)
**And** `invoice_number` is generated in `INV-YYYY-NNNN` format

**Given** OPS reviews and confirms the invoice
**When** they click "Send Invoice"
**Then** invoice status changes from `draft` to `sent`
**And** the customer is notified via Telegram with invoice summary

**Definition of Done:**
- [ ] Feature works: Invoice generation with auto-populated line items
- [ ] Tests pass: Generation, calculation, PDF output, status transition tests
- [ ] Contextual logging: Invoice generation logged to `web` channel with `invoice_id`, `job_id`, `customer_id`, `total_cents` context (D24)

---

### Story 7.2: QuickBooks Export

As an **Accounting Staff member**,
I want to export financial data in a QuickBooks-compatible format,
So that I can import transactions into our accounting system without manual entry.

**Implementation Units:**
1. **Migration:** N/A (uses `exported_at` timestamp on `invoices` table)
2. **Model/Logic:** `QuickBooksExportService.generateCsv()` with correct column mapping (NFR13), date range filtering, batch export
3. **Blade/Livewire:** Export page with date range picker, download button, export history
4. **Pest Test:** CSV format validation, column mapping, date range filtering, re-export prevention

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** Accounting navigates to `/financial/export` and selects a date range (FR44)
**When** they click "Export to QuickBooks"
**Then** a CSV file is generated with QuickBooks-compatible columns (Date, Type, Num, Name, Memo, Amount)
**And** `exported_at` is set on each included invoice to prevent duplicates
**And** the file downloads automatically

**Definition of Done:**
- [ ] Feature works: CSV export with correct QuickBooks format
- [ ] Tests pass: CSV format, column mapping, duplicate prevention tests
- [ ] Contextual logging: Export logged to `web` channel with `export_date_range`, `invoice_count`, `total_amount` context (D24)

---

### Story 7.3: Management Dashboard and KPIs

As a **Manager**,
I want to see a dashboard with key performance indicators and trend charts,
So that I can make data-driven decisions about the business.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `DashboardAnalyticsService` for KPI calculations (booking volume, revenue, margin, top customers, carrier performance)
3. **Blade/Livewire:** `ManagementDashboard` Livewire component with stat widgets, ApexCharts for trends (volume, revenue, margin over time), filterable by date range
4. **Pest Test:** KPI calculations, chart data endpoints, date range filtering, RBAC (Manager+ only)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** a Manager navigates to `/dashboard/management` (FR45)
**When** the dashboard loads within 3 seconds (NFR1)
**Then** stat widgets display: Total Bookings (this month), Revenue (USD), Average Margin %, Active Jobs, Top 5 Customers
**And** ApexCharts show: booking volume trend (line), revenue trend (bar), margin trend (area)
**And** a date range picker allows filtering

**Empty State:** "Insufficient data for reports. Check back after first month."

**Definition of Done:**
- [ ] Feature works: Dashboard with KPI widgets and charts
- [ ] Tests pass: KPI calculation, chart data, RBAC tests
- [ ] Contextual logging: Dashboard access logged to `web` channel with `user_role`, `date_range` context (D24)

---

### Story 7.4: Profit-Per-Booking Calculation

As a **Manager**,
I want to see the profit margin for each booking,
So that I can identify profitable routes and customers.

**Implementation Units:**
1. **Migration:** N/A (uses `customer_rate_cents` and `carrier_cost_cents` on `jobs` table)
2. **Model/Logic:** `ProfitAnalysisService.calculateJobProfit()` (customer rate - carrier cost), aggregation by customer/route/service type
3. **Blade/Livewire:** Profit column on job/invoice tables, profit breakdown in job detail
4. **Pest Test:** Profit calculation, edge cases (missing rates), aggregation accuracy

**Acceptance Criteria:**

**Given** a job has both `customer_rate_cents` and `carrier_cost_cents` set (FR46)
**When** profit is calculated
**Then** profit margin = `customer_rate_cents - carrier_cost_cents` displayed in USD
**And** percentage margin is shown as a badge (green >20%, yellow 10-20%, red <10%)

**Given** the profit analysis page is accessed
**When** data is displayed
**Then** grouping by customer, route, and service type is available with totals

**Definition of Done:**
- [ ] Feature works: Profit calculated and displayed per job
- [ ] Tests pass: Calculation, edge cases, aggregation tests
- [ ] Contextual logging: Profit calculation logged to `web` channel with `job_id`, `profit_cents`, `margin_percent` context (D24)

---

### Story 7.5: Job History and Audit Trail Viewer

As an **OPS Manager**,
I want to view the complete history and audit trail for any job,
So that I can track every change and action taken on a shipment.

**Implementation Units:**
1. **Migration:** N/A (uses events/audit data from previous epics)
2. **Model/Logic:** `AuditTrailService.getJobHistory()` aggregating status changes, assignments, comments, and document actions
3. **Blade/Livewire:** Timeline component in job detail showing chronological events with user attribution
4. **Pest Test:** Timeline rendering, event aggregation, RBAC

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an OPS Manager views a job detail (FR47)
**When** they open the "History" tab
**Then** a timeline shows all events: status changes, carrier assignments, OPS assignments, comments, document uploads, override actions
**And** each event shows: timestamp, user who performed it, before/after values

**Definition of Done:**
- [ ] Feature works: Job history timeline with all events
- [ ] Tests pass: Event aggregation, timeline rendering tests
- [ ] Contextual logging: Audit trail access logged to `web` channel with `job_id`, `events_count` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 8: ADMINISTRATION & SYSTEM CONFIGURATION               -->
<!-- ============================================================ -->

## Epic 8: Administration & System Configuration

**Goal:** Admins manage users, roles, rate cards, carriers, and system settings from the Back-Office.

---

### Story 8.1: User Account Management

As an **Admin**,
I want to create, edit, and deactivate user accounts,
So that I can control who has access to the system.

**Implementation Units:**
1. **Migration:** N/A (uses `users` table from Epic 0)
2. **Model/Logic:** `UserManagementService` for CRUD, account activation/deactivation, Telegram binding
3. **Blade/Livewire:** User list with role badges, create/edit form, activate/deactivate toggle
4. **Pest Test:** CRUD, deactivation blocks login, RBAC (Admin only)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an Admin navigates to `/admin/users` (FR48)
**When** the page loads
**Then** a table shows all users with: Name, Email, Role, Telegram Status, Active/Inactive, Last Login

**Given** an Admin creates a new user with role "OPS"
**When** they save the form
**Then** the user account is created with role assigned and Telegram binding instructions provided

**Given** an Admin deactivates a user
**When** `is_active` is set to false
**Then** the user cannot log in and their active sessions are invalidated

**Definition of Done:**
- [ ] Feature works: User CRUD with activation/deactivation
- [ ] Tests pass: CRUD, login prevention, RBAC tests
- [ ] Contextual logging: User management logged to `audit` channel with `target_user_id`, `action`, `performed_by_user_id` context (D24)

---

### Story 8.2: RBAC Role Assignment

As an **Admin**,
I want to assign and change roles for user accounts,
So that I can control what each user can access in the system.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** `RbacService.changeRole()` with validation (can't remove last SADM), audit trail
3. **Blade/Livewire:** Role selector in user edit form, role change confirmation modal
4. **Pest Test:** Role change, last-SADM protection, audit trail, permission matrix verification

**Acceptance Criteria:**

**Given** an Admin edits a user account (FR49)
**When** they change the role from "OPS" to "OPS Manager"
**Then** the user's role is updated and their sidebar reflects the new permissions
**And** the role change is recorded in the audit trail

**Given** an Admin tries to remove the last System Admin role
**When** the role change is attempted
**Then** the system prevents it: "Cannot remove the last System Admin"

**Definition of Done:**
- [ ] Feature works: Role assignment and change
- [ ] Tests pass: Role change, last-SADM protection, audit trail tests
- [ ] Contextual logging: Role change logged to `audit` channel with `target_user_id`, `old_role`, `new_role`, `changed_by_user_id` context (D24)

---

### Story 8.3: Rate Card Configuration

As an **Admin**,
I want to configure rate cards for customers and carriers,
So that pricing is managed centrally and applied automatically.

**Implementation Units:**
1. **Migration:** `create_rate_cards_table` (polymorphic for Customer/Carrier)
2. **Model/Logic:** `RateCard` model with `HasUuid`, `RateCardService` for CRUD, polymorphic relationship, effective date ranges
3. **Blade/Livewire:** Rate card management page with customer/carrier filter, create/edit form, effective date range picker, rate history
4. **Pest Test:** CRUD, polymorphic queries, date range validation, rate lookup, RBAC

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an Admin navigates to `/admin/rate-cards` (FR50)
**When** they create a rate card for a customer on a route
**Then** the rate card is saved with: `rateable_type`, `rateable_id`, `service_type`, `origin`, `destination`, `rate_cents`, `effective_from`
**And** Khmer location names are supported (D27)

**Given** a rate card's `effective_until` date passes
**When** the system looks up rates for a new booking
**Then** only active rates (where `effective_until` is NULL or in the future) are returned

**Definition of Done:**
- [ ] Feature works: Rate card CRUD with effective date ranges
- [ ] Tests pass: CRUD, polymorphic queries, date range, lookup tests
- [ ] Contextual logging: Rate card changes logged to `web` channel with `rate_card_id`, `rateable_type`, `action` context (D24)

---

### Story 8.4: Carrier Database Management

As an **Admin**,
I want to manage the carrier database with approval workflows,
So that only verified carriers can receive job assignments.

**Implementation Units:**
1. **Migration:** N/A (uses `carriers` table from Epic 4)
2. **Model/Logic:** Carrier approval workflow (`is_approved` toggle), insurance/license expiry tracking
3. **Blade/Livewire:** Carrier management page with approval actions, compliance indicators (license/insurance expiry badges)
4. **Pest Test:** Approval flow, expiry tracking, unapproved carriers blocked from assignment

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** an Admin views the carrier list (FR51)
**When** a carrier has `is_approved: false`
**Then** a yellow "Pending Approval" badge is displayed
**And** they cannot be assigned to jobs until approved

**Given** a carrier's `insurance_expiry` is within 30 days
**When** the admin views the carrier list
**Then** a warning badge: "Insurance expiring soon" is displayed

**Definition of Done:**
- [ ] Feature works: Carrier approval and compliance tracking
- [ ] Tests pass: Approval flow, assignment blocking, expiry detection tests
- [ ] Contextual logging: Carrier approval logged to `web` channel with `carrier_id`, `action`, `approved_by_user_id` context (D24)

---

### Story 8.5: System Settings Configuration

As a **System Admin**,
I want to configure system settings from the Back-Office,
So that operational parameters can be adjusted without code changes.

**Implementation Units:**
1. **Migration:** `SystemConfigSeeder` with default values
2. **Model/Logic:** `SystemConfigService` for get/set with caching (Redis), type validation
3. **Blade/Livewire:** Settings page grouped by category (Tracking, Notifications, General), form with save and reset to defaults
4. **Pest Test:** Config CRUD, cache invalidation, seeder defaults, RBAC (SADM only)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** a System Admin navigates to `/admin/settings` (FR52)
**When** they view the settings page
**Then** settings are grouped by category: Tracking (nag_interval_minutes, silence_threshold_minutes, geofence_default_radius), Notifications (escalation_threshold), General (items_per_page)

**Given** a SADM updates `nag_interval_minutes` from 30 to 45
**When** they save
**Then** the value is updated in `system_configs` and Redis cache is invalidated
**And** a success toast: "✅ Settings saved"

**Definition of Done:**
- [ ] Feature works: System settings CRUD with caching
- [ ] Tests pass: Config CRUD, cache invalidation, seeder tests
- [ ] Contextual logging: Config change logged to `audit` channel with `config_key`, `old_value`, `new_value`, `changed_by_user_id` context (D24)

---

<!-- ============================================================ -->
<!-- EPIC 9: CROSS-CUTTING QUALITY & OBSERVABILITY               -->
<!-- ============================================================ -->

## Epic 9: Cross-Cutting Quality & Observability

**Goal:** System is production-hardened with audit trails, performance validation, exception handling, and event chain testing.

---

### Story 9.1: Audit Trail Service and Audit Logs

As a **System Admin**,
I want all significant actions recorded in a searchable audit log,
So that there is a complete trail of who did what and when.

**Implementation Units:**
1. **Migration:** `create_audit_logs_table` (immutable, no updated_at/soft deletes)
2. **Model/Logic:** `AuditLog` model with `HasUuid`, `AuditService` with automatic tracking via `Auditable` trait, polymorphic auditing for all business entities
3. **Blade/Livewire:** Audit log viewer with filters (user, action, entity type, date range), searchable
4. **Pest Test:** Auto-audit on model events, manual audit entries, query performance, 1-year retention (NFR10)

**Visual Acceptance:** Must match the layout and spacing defined in `docs/design/dashboard_v1.png` as verified by the Antigravity Browser artifact.

**Acceptance Criteria:**

**Given** any tracked model (Job, Booking, Customer, etc.) is created, updated, or deleted
**When** the `Auditable` trait fires
**Then** an `audit_logs` record is created with: `user_id`, `auditable_type/id`, `action`, `old_values`, `new_values`, `metadata` (including `request_id`)

**Given** a System Admin navigates to `/admin/audit-logs`
**When** they filter by user and date range
**Then** matching audit entries are displayed with before/after diffs and action details

**Definition of Done:**
- [ ] Feature works: Automatic audit trail for all tracked models
- [ ] Tests pass: Auto-audit, manual audit, filter, performance tests
- [ ] Contextual logging: Audit service uses `audit` channel with full context (D24)

---

### Story 9.2: Event-Listener Integration Testing

As a **Developer Agent**,
I want comprehensive integration tests for all event-listener chains,
So that notification flows work correctly end-to-end.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** Integration test suite covering all event chains: `JobCreated` → listeners, `JobStatusUpdated` → listeners, `CarrierJobAccepted` → listeners, `DriverLocationUpdated` → listeners, `SilenceEscalation` → listeners, `GeofenceTriggered` → listeners
3. **Blade/Livewire:** N/A
4. **Pest Test:** Event dispatch verification, listener execution, side-effect validation for each chain

**Acceptance Criteria:**

**Given** the event system has been built across Epics 2-6
**When** integration tests run for each event chain
**Then** every registered listener fires and produces correct side effects:
- `JobCreated` → Kanban updates, OPS notification
- `JobStatusUpdated` → Customer notification (if applicable), audit log
- `CarrierJobAccepted` → Job status update, OPS notification
- `DriverLocationUpdated` → Map marker update, geofence check
- `SilenceEscalation` → OPS exception card, driver notification
- `GeofenceTriggered` → Job status auto-update, customer notification

**Definition of Done:**
- [ ] Feature works: All event chains execute correctly
- [ ] Tests pass: Integration tests for every event → listener chain
- [ ] Contextual logging: Event dispatch logged to appropriate channels (D24)

---

### Story 9.3: Performance Validation Against NFR Thresholds

As a **Developer Agent**,
I want automated tests verifying the system meets all NFR performance thresholds,
So that performance regressions are caught before deployment.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** Performance test suite with timing assertions
3. **Blade/Livewire:** N/A
4. **Pest Test:** Response time tests for dashboard (<3s NFR1), bot (<2s NFR2), location (<10s NFR3), OCR (<30s NFR4)

**Acceptance Criteria:**

**Given** the system is loaded with realistic data volumes (100 customers, 500 bookings, 200 jobs)
**When** performance tests run
**Then** all NFR thresholds are met:
- NFR1: Dashboard page loads in <3 seconds
- NFR2: Bot commands respond in <2 seconds
- NFR3: Location tracking updates display in <10 seconds
- NFR4: OCR processing completes in <30 seconds

**Definition of Done:**
- [ ] Feature works: System meets all performance thresholds
- [ ] Tests pass: Performance tests with timing assertions
- [ ] Contextual logging: Performance results logged to `web` channel with `endpoint`, `response_time_ms`, `threshold_ms` context (D24)

---

### Story 9.4: Exception Handler and Error State Verification

As a **Developer Agent**,
I want to verify that all domain exceptions return correct HTTP codes and render user-friendly error states,
So that the system degrades gracefully and users always see actionable error messages.

**Implementation Units:**
1. **Migration:** N/A
2. **Model/Logic:** Exception handler verification tests for all mapped exceptions (D24 table)
3. **Blade/Livewire:** Error state component rendering tests (`<x-error-state>`, `<x-empty-state>`, toast notifications)
4. **Pest Test:** Each exception → correct HTTP code, error code, user message; branded error components render correctly

**Acceptance Criteria:**

**Given** the exception mapping table from the Architecture document (D24)
**When** each domain exception is thrown
**Then** the correct HTTP status code and error code are returned
**And** JSON responses use the standard envelope format: `{success: false, error: {code, message}}`
**And** Livewire/web responses show branded `<x-error-state>` components (not raw stack traces)
**And** Telegram bot responses show user-friendly emoji messages with `/help` guidance

**Definition of Done:**
- [ ] Feature works: All exceptions handled gracefully across all channels
- [ ] Tests pass: Exception mapping tests for all 12+ domain exceptions
- [ ] Contextual logging: Exception handling verified in all channels (web, bot, JSON) per D24
