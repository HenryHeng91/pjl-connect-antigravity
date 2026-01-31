---
stepsCompleted: [1, 2]
inputDocuments: ['_bmad-output/planning-artifacts/product-brief-PJL-Connect-2026-01-24.md', '_bmad-output/planning-artifacts/prd.md']
brandAssets: ['logos/pjl-logo-vertical.jpg', 'logos/pjl-logo-horizontal.png']
framework: 'Laravel 11'
componentAnnotation: true
---

# UX Design Specification: PJL Connect

**Author:** Siekhai  
**Date:** 2026-01-31  
**Framework:** Laravel 11 (Blade Components + Livewire)

---

## Executive Summary

### Project Vision

PJL Connect is an **"Invisible App" Multi-Modal Logistics Operating System** that transforms manual chaos into automated harmony. The system meets users where they already live—Telegram—while providing a powerful Laravel-based Back-Office for operations staff.

**Core Philosophy:** Automate 99% of "happy path" operations so humans only handle the 1% of exceptions.

**Hybrid Architecture:**
- **Web Back-Office** (Laravel 11 + Livewire) → OPS, Broker, Accounting, Management
- **Telegram Bots** → Customer, Driver, Carrier, OPS Internal

### Target Users

| Persona | Pain Level | Primary Need | Interface |
|---------|-----------|--------------|-----------|
| **OPS Personnel** | 🔥🔥🔥 | Escape Excel hell, focus on exceptions | Desktop Back-Office |
| **Customer** | 🔥🔥 | Stop asking "where is my truck?" | Telegram Bot |
| **Driver** | 🔥🔥 | No new apps, minimal taps | Telegram Bot |
| **Carrier** | 🔥 | Get jobs instantly, high utilization | Telegram Bot |
| **Broker** | 🔥🔥 | Copy-Paste Magic for ASYCUDA | Desktop Back-Office |
| **Accounting** | 🔥 | One-click ledger sync | Desktop Back-Office |
| **Management** | 🔥 | Real-time profit visibility | Desktop Dashboard |

### Key Design Challenges

1. **Hybrid Interface Cohesion:** Web Back-Office (Laravel) + Telegram Bots must feel like ONE unified system
2. **Zero Learning Curve:** OPS team must feel empowered, not trapped by automation
3. **Mobile-First Execution:** Drivers/Carriers tap buttons on the road—no typing required
4. **Trust Building:** Customers must trust bot updates over human phone calls
5. **Exception-First Design:** Surface problems, hide routine operations

### Design Opportunities

1. **Kanban Magic:** Job board that *feels* like moving cards, powered by Livewire real-time updates
2. **Visual Receipt Cards:** Bot messages that look premium, not generic
3. **Exception-First Dashboard:** OPS becomes "air traffic control" for logistics
4. **Brand Expression:** Deep Teal (#1E5A6B) + Sky Blue (#5BC0DE) across all touchpoints
5. **Copy-Paste Magic:** Transform tedious ASYCUDA data entry into one-click blocks

### Laravel Component Architecture

| Pattern | Usage |
|---------|-------|
| **Blade Components** | Reusable UI atoms (`<x-button>`, `<x-card>`, `<x-badge>`, `<x-status-pill>`) |
| **Livewire Components** | Real-time Kanban, live tracking maps, dashboard counters, data tables |
| **Alpine.js** | Micro-interactions, dropdowns, modals, toast notifications |
| **Layouts** | `layouts/app.blade.php`, `layouts/auth.blade.php`, `layouts/dashboard.blade.php` |
| **Partials** | `partials/sidebar.blade.php`, `partials/header.blade.php`, `partials/footer.blade.php` |
