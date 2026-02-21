# Master Sprint Plan — PJL Connect

> Generated: 2026-02-21 · Source: `epics.md` (47 stories, 10 epics)

---

## 2-Point Quality Check Results

### ✅ Check 1: Dependency & Blocker Mapping

Every story has been tagged with its **blocker type** below. The dependency chain is strictly forward-only — no story depends on a future story.

**Critical Path (blocks multiple epics):**

```
0.1 Boost → 0.2 TailAdmin → 0.3 Auth/DB → 0.4 RBAC → 0.5 Day2Ops
                                   ↓
                              1.1 Customers
                                   ↓
                         2.1 Bookings → 2.2 Documents/OCR
                                              ↓
                                    3.1 Jobs + Shipment Legs
                                    ↓          ↓         ↓
                              4.1 Carriers  5.x Track  6.x Compliance
                                    ↓
                              4.4 Location Pings
                                    ↓
                              4.5 + 5.x Map/Tracking
```

**Cross-Epic Blockers (13):**

| Blocker Story | Creates | Unblocks |
|---------------|---------|----------|
| 0.1 Boost + MCP | MCP Server | ALL stories |
| 0.2 TailAdmin | Dashboard shell | ALL frontend stories |
| 0.3 Auth + DB | `users`, `system_configs`, auth guards | ALL authenticated features |
| 0.4 RBAC | `CheckRole` middleware | ALL route-protected features |
| 0.5 Day 2 Ops | Log channels, exception handler, `InjectLogContext` | ALL stories requiring contextual logging |
| 0.6 CI/CD | GitHub Actions pipeline | ALL deployments |
| 1.1 Customer Reg | `customers` table | Epic 2, 7 (bookings, invoices) |
| 2.1 Booking via Bot | `bookings` table | Epic 3 (job conversion) |
| 2.2 Doc Upload + OCR | `documents` table | Stories 2.3–2.7, Epic 3 (doc features) |
| 3.1 Booking→Job | `jobs`, `shipment_legs` tables | Epic 4, 5, 6, 7 |
| 4.1 Carrier Reg | `carriers`, `drivers` tables | Stories 4.2–4.5 |
| 4.4 Driver Location | `location_pings` table | Story 4.5, Epic 5 (map/tracking) |
| 6.2 ASYCUDA Magic | `sad_declarations` table | Story 6.3 (SAD review) |

**Within-Epic Sequential Dependencies (6):**

| Dependency | Reason |
|------------|--------|
| 2.2 → 2.3 → 2.4 | OCR → Confidence Routing → Receipt Card (data flows sequentially) |
| 3.1 → 3.2 | Jobs table needed before Kanban can display jobs |
| 4.1 → 4.2 → 4.3 | Carrier registration → notifications → nag-bot |
| 4.4 → 4.5 | Location pings must exist before map can display |
| 7.1 → 7.2 | Invoices must exist before QuickBooks export |
| 8.3 → 7.1 | Rate cards created before invoice auto-population |

---

### ✅ Check 2: Observability & Debugging — Baked In, Not Bolted On

**Architecture compliance verified:** Logging and error handling are NOT deferred to Epic 9. They are enforced at the **Definition of Done** level of EVERY story:

> **DoD Rule 3:** *"Contextual logging has been implemented for the core logic as per the Architect's plan (D24)"*

This means a Developer Agent **cannot close ANY story** without implementing:
- ✅ Correct log channel (`web`, `bot`, `ocr`, `audit`, `queue`)
- ✅ Contextual fields (e.g., `job_id`, `user_id`, `request_id`)
- ✅ Domain exceptions using `DomainException` base class + correct HTTP codes
- ✅ User-friendly error messages per the Architecture exception mapping table

**Per-Epic Observability Checkpoints:**

| Epic | Log Channel | Domain Exceptions Created | Key Context Fields |
|------|-------------|---------------------------|-------------------|
| 0 | `web`, `audit` | `InsufficientPermissionException` | `auth_guard`, `login_method`, `user_role` |
| 1 | `bot`, `web` | *(none — uses base exceptions)* | `telegram_user_id`, `customer_id` |
| 2 | `bot`, `ocr` | `OcrProcessingException`, `UnsupportedDocumentTypeException`, `DuplicateBookingException` | `booking_id`, `confidence_score`, `file_type`, `duration_ms` |
| 3 | `web`, `audit` | `InvalidJobStatusTransitionException`, `JobAlreadyAssignedException`, `JobNotFoundException` | `job_id`, `old_status`, `new_status`, `override_reason` |
| 4 | `bot` | `CarrierUnavailableException` | `carrier_id`, `nag_count`, `response_time_ms` |
| 5 | `web`, `bot` | `DriverLocationExpiredException` | `driver_id`, `last_ping_age_minutes`, `distance_meters` |
| 6 | `web`, `bot` | *(reuses job exceptions)* | `sad_id`, `deadline_date`, `days_remaining` |
| 7 | `web` | *(reuses base exceptions)* | `invoice_id`, `total_cents`, `margin_percent` |
| 8 | `audit` | *(reuses RBAC exceptions)* | `target_user_id`, `config_key`, `old_value`, `new_value` |
| 9 | ALL | *(verification of all above)* | *(validates all above are wired correctly)* |

**Epic 9's role clarified:** Epic 9 is NOT "add logging later." It is a **verification & hardening** pass that:
- 9.1 adds the `audit_logs` table + `Auditable` trait (auto-audit for all models)
- 9.2 verifies event→listener chains fire correctly end-to-end
- 9.3 validates NFR performance thresholds under load
- 9.4 verifies ALL 14 domain exceptions → correct HTTP codes + messages

---

## Master Sprint Plan

### Sprint 0: Infrastructure & Environment Setup
**Duration:** 1 week · **Epic:** 0 · **Stories:** 6

> **Goal:** A deployable Laravel 12 shell with Boost, TailAdmin, dual auth, RBAC, logging, and CI/CD.
> **Why first:** Every other story depends on this foundation.

| Order | Story | Title | Blocker For | Dev Effort |
|-------|-------|-------|-------------|------------|
| 1 | 0.1 | Install Laravel Boost + MCP Server | ALL | XS |
| 2 | 0.2 | TailAdmin + Tailwind + Alpine + Livewire | All frontend | S |
| 3 | 0.3 | Database Foundation + Dual Auth | All auth features | M |
| 4 | 0.4 | RBAC Middleware + Role Seeder | All protected routes | S |
| 5 | 0.5 | Day 2 Ops (Health, Logging, Exceptions) | All logging/error handling | M |
| 6 | 0.6 | CI/CD Pipeline (GitHub Actions) | All deployments | S |

**Sprint 0 Definition of Done:**
- [ ] `php artisan boost:skills` responds
- [ ] Dashboard shell renders at `/` with TailAdmin layout
- [ ] Telegram login + SysAdmin 2FA login both work
- [ ] RBAC blocks unauthorized routes (403)
- [ ] `GET /up` returns healthy status
- [ ] All log channels configured and writing
- [ ] CI pipeline passes on clean commit

---

### Sprint 1: Customer Foundation & Booking Intake
**Duration:** 1.5 weeks · **Epics:** 1, 2 · **Stories:** 9

> **Goal:** Customers register via Telegram, create bookings, upload documents with OCR, and OPS manages bookings.
> **Depends on:** Sprint 0 ✅

| Order | Story | Title | Blocker For |
|-------|-------|-------|-------------|
| 1 | 1.1 | Customer Registration via `/start` | Bookings, Invoices |
| 2 | 1.2 | Customer Profile Management (Back-Office) | — |
| 3 | 2.1 | Booking via `/new` + `bookings` table | Job conversion |
| 4 | 2.2 | Document Upload + OCR Pipeline + `documents` table | OCR routing, doc features |
| 5 | 2.3 | OCR Confidence Routing + Confirmation | Receipt Card |
| 6 | 2.4 | Visual Receipt Card Generation | — |
| 7 | 2.5 | Booking List + Filtering (Back-Office) | — |
| 8 | 2.6 | OPS Manual Booking Creation | — |
| 9 | 2.7 | Document Attachment (Back-Office) | — |

---

### Sprint 2: Job Management & OPS Command Center
**Duration:** 2 weeks · **Epic:** 3 · **Stories:** 8

> **Goal:** Complete job lifecycle management — Kanban, assignment, status, comments, documents, printing.
> **Depends on:** Sprint 1 ✅ (bookings + documents exist)

| Order | Story | Title | Blocker For |
|-------|-------|-------|-------------|
| 1 | 3.1 | Booking→Job Conversion + `jobs`/`shipment_legs` | ALL remaining epics |
| 2 | 3.2 | Kanban Board (drag-drop, real-time) | — |
| 3 | 3.3 | Carrier Assignment Modal | Carrier notifications |
| 4 | 3.4 | Status Override (cancel, on-hold) | — |
| 5 | 3.5 | Job Comments & Notes | — |
| 6 | 3.6 | OPS Assignment (auto + manual) | — |
| 7 | 3.7 | Document Preview, Print, Annotation | — |
| 8 | 3.8 | Booking Summary Cover Page Print | — |

---

### Sprint 3: Carrier & Driver Operations
**Duration:** 1.5 weeks · **Epic:** 4 · **Stories:** 5

> **Goal:** Carrier Telegram notifications, accept/decline, Nag-Bot, driver live GPS sharing + map view.
> **Depends on:** Sprint 2 ✅ (jobs + carrier assignment exist)

| Order | Story | Title | Blocker For |
|-------|-------|-------|-------------|
| 1 | 4.1 | Carrier Registration + `carriers`/`drivers` tables | Carrier bot, Nag-Bot |
| 2 | 4.2 | Job Notification + Accept/Decline via Bot | Nag-Bot |
| 3 | 4.3 | Nag-Bot + OPS Escalation | — |
| 4 | 4.4 | Driver Location Sharing + `location_pings` table | Map, tracking |
| 5 | 4.5 | Real-Time Driver Map View (Leaflet.js) | — |

---

### Sprint 4: Tracking, Monitoring & Compliance
**Duration:** 2 weeks · **Epics:** 5, 6 · **Stories:** 9

> **Goal:** Customer `/track`, silence escalation, geofences, ASYCUDA Copy-Paste Magic, SAD review, deadlines.
> **Depends on:** Sprint 3 ✅ (location pings + carriers exist)

| Order | Story | Title | Blocker For |
|-------|-------|-------|-------------|
| 1 | 5.1 | `/track` Command (Customer Bot) | — |
| 2 | 5.2 | GPS Blackout Detection + Silence Escalation | — |
| 3 | 5.3 | Manual Vessel/Flight Tracking Entry | — |
| 4 | 5.4 | ETA Calculation | — |
| 5 | 5.5 | Geofence Management + Auto-Trigger + `geofences` table | — |
| 6 | 6.1 | Job Document Viewer | — |
| 7 | 6.2 | ASYCUDA Copy-Paste Magic + `sad_declarations` table | SAD review |
| 8 | 6.3 | SAD Review Workbench | — |
| 9 | 6.4 | Deadline Tracking + Customer Reminders | — |

---

### Sprint 5: Financial, Reporting & Administration
**Duration:** 2 weeks · **Epics:** 7, 8 · **Stories:** 10

> **Goal:** Invoicing, QuickBooks export, KPI dashboard, profit analysis, user/role management, rate cards, system settings.
> **Depends on:** Sprint 2 ✅ (jobs exist); Sprint 1 ✅ (customers exist)
> **Note:** Can partially overlap with Sprint 4 (Epics 5/6) since both depend on Sprint 2.

| Order | Story | Title | Blocker For |
|-------|-------|-------|-------------|
| 1 | 8.3 | Rate Card Configuration + `rate_cards` table | Invoice auto-populate |
| 2 | 7.1 | Invoice Generation + `invoices`/`line_items` tables | QB export |
| 3 | 7.2 | QuickBooks Export | — |
| 4 | 7.3 | Management Dashboard + KPIs | — |
| 5 | 7.4 | Profit-Per-Booking Calculation | — |
| 6 | 7.5 | Job History & Audit Trail Viewer | — |
| 7 | 8.1 | User Account Management | — |
| 8 | 8.2 | RBAC Role Assignment | — |
| 9 | 8.4 | Carrier Database Management (approval flow) | — |
| 10 | 8.5 | System Settings Configuration | — |

---

### Sprint 6: Quality Hardening & Production Readiness
**Duration:** 1 week · **Epic:** 9 · **Stories:** 4

> **Goal:** Verify ALL observability, audit trails, event chains, performance thresholds, and exception handling work end-to-end.
> **Depends on:** All previous sprints ✅
> **Note:** This is VERIFICATION, not initial implementation. Logging/exceptions were built in every prior sprint.

| Order | Story | Title | Purpose |
|-------|-------|-------|---------|
| 1 | 9.1 | Audit Trail Service + `audit_logs` table + `Auditable` trait | Automated model auditing |
| 2 | 9.2 | Event→Listener Integration Tests | Verify all 6+ event chains |
| 3 | 9.3 | Performance Validation (NFR thresholds) | Dashboard <3s, Bot <2s, OCR <30s |
| 4 | 9.4 | Exception Handler Verification | All 14 domain exceptions map correctly |

---

## Sprint Summary

| Sprint | Duration | Epics | Stories | Cumulative |
|--------|----------|-------|---------|------------|
| Sprint 0 | 1 week | Epic 0 | 6 | 6 |
| Sprint 1 | 1.5 weeks | Epic 1, 2 | 9 | 15 |
| Sprint 2 | 2 weeks | Epic 3 | 8 | 23 |
| Sprint 3 | 1.5 weeks | Epic 4 | 5 | 28 |
| Sprint 4 | 2 weeks | Epic 5, 6 | 9 | 37 |
| Sprint 5 | 2 weeks | Epic 7, 8 | 10 | 47 |
| Sprint 6 | 1 week | Epic 9 | 4 | **47** |
| **Total** | **~11 weeks** | **10 epics** | **47 stories** | |

## Parallelization Opportunities

```
Sprint 0 ─────────────────────> (sequential, foundation)
Sprint 1 ─────────────────────> (sequential, builds on 0)
Sprint 2 ─────────────────────> (sequential, builds on 1)
Sprint 3 ──────────> ┐
                      ├──> (can run in parallel after Sprint 2)
Sprint 4 ──────────> ┘
Sprint 5 ─────────────────────> (can start after Sprint 2, parallel with 3/4)
Sprint 6 ─────────────────────> (after all others complete)
```

**With parallelization:** Sprints 3, 4, and 5 can overlap → **~8.5 weeks total**
