---
stepsCompleted: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
inputDocuments: ['_bmad-output/planning-artifacts/product-brief-PJL-Connect-2026-01-24.md', '_bmad-output/planning-artifacts/prd.md']
brandAssets: ['logos/pjl-logo-vertical.jpg', 'logos/pjl-logo-horizontal.png']
framework: 'Laravel 11'
componentAnnotation: true
lastStep: 14
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

---

## Core User Experience

### Defining Experience

PJL Connect operates as an **orchestrated ecosystem** where three user groups function with equal priority:

| User Group | Core Loop | Interface |
|------------|-----------|-----------|
| **OPS Personnel** | Glance at exception board → Handle what needs attention → Move to next | Web Back-Office (Desktop + Mobile) |
| **Customers/Drivers/Carriers** | Receive proactive updates → Tap button when action needed → Done | Telegram Bots |
| **Brokers** | Receive shipment data → One-click copy-paste to ASYCUDA → Confirm | Web Back-Office |

**Design Philosophy:** No single "hero" action—the system's value emerges from the seamless orchestration between all three groups working in harmony.

### Platform Strategy

| Platform | Primary Use | Priority |
|----------|-------------|----------|
| **Desktop Back-Office** | Full OPS workflow, Broker ASYCUDA, Accounting, Management dashboards | Primary |
| **Mobile Back-Office** | Full responsive experience—OPS can handle emergencies anywhere | Equal to Desktop |
| **Telegram Bots** | Customer updates, Driver GPS/status, Carrier job acceptance | Primary for field users |

**Responsive Design Mandate:** Desktop and mobile Back-Office must provide identical capabilities—no "lite" mode compromises.

### Effortless Interactions

These three interactions should feel **magical**:

1. **Kanban Auto-Updates:** Job cards move in real-time as drivers send GPS pings via Telegram—no refresh needed, no manual status changes
2. **Exception Alerts + AI Suggestions:** Problems bubble up with pre-filled resolution options—OPS clicks to confirm, not to investigate
3. **One-Click ASYCUDA Blocks:** Brokers see formatted copy-paste blocks that drop directly into ASYCUDA—zero reformatting

### Critical Success Moments

**The "I Love This" Moment:**
> *OPS person looks at a colleague and says: "Why didn't we do this years ago?"*

**New OPS Onboarding Flow:**
- **Shadow Mode First:** New hires watch existing OPS handle 2-3 exceptions live
- **Guided Practice:** Then handle exceptions with supervisor oversight
- **Solo Mode:** Confidence built through observation before action

**Trust Breakers (MUST AVOID):**

| Anti-Pattern | Why It Destroys Trust |
|--------------|----------------------|
| **Silent Loading** | User doesn't know if action worked |
| **Missing Tooltips** | User feels lost, not empowered |
| **Slow Submit/Load** | User switches back to "the old way" |
| **Stale Data** | User questions everything they see |

### Experience Principles

These principles will guide every UX decision in PJL Connect:

1. **🎭 Always Be Informative:** Every action shows loading states, every element has tooltips, every result confirms success
2. **⚡ Speed Is Trust:** Sub-second responses for common actions, optimistic UI updates, perceived instant feedback
3. **🎯 Exception-First Design:** Automate 99% of routine operations; humans focus on the 1% that needs judgment
4. **📱 Responsive Parity:** Mobile and desktop Back-Office are equals—no capability compromises
5. **👁️ Shadow Before Solo:** New users observe before participating; confidence through exposure

---

## Desired Emotional Response

### Primary Emotional Goals

| User Group | Primary Emotion | Supporting Emotion |
|------------|-----------------|-------------------|
| **OPS Personnel** | **Empowered** — Air traffic controller, not helpless passenger | Calm, In Control |
| **Customer** | **Informed** — Never wondering "what's happening?" | Respected, Trusted |
| **Driver** | **Respected** — Minimal friction, time valued | Confident, Guided |
| **Carrier** | **Valued** — Jobs come to them, high utilization | Trusted, Professional |
| **Broker** | **Efficient** — Copy-paste magic eliminates tedium | Accomplished |
| **Accounting** | **Confident** — Data is accurate and complete | Relieved |
| **Management** | **Informed** — Real-time visibility, no surprises | Secure |

### Emotional Journey Mapping

| Stage | Desired Emotion | Design Implication |
|-------|-----------------|-------------------|
| **First Discovery** | "This looks professional—I can trust this" | Premium visual design, clear value proposition |
| **During Core Action** | "This just works—I don't have to think" | Intuitive flows, smart defaults, zero friction |
| **After Task Complete** | "That was easy—I'm ahead of schedule" | Clear success feedback, next-action suggestions |
| **When Something Goes Wrong** | "I know exactly what happened and what to do next" | Informative errors, AI-suggested resolutions |
| **Returning User** | "My dashboard knows me—I'm home" | Personalized views, remembered preferences |

### Micro-Emotions Priority Matrix

| ✅ Critical to Achieve | ❌ Must Avoid | Design Response |
|------------------------|--------------|-----------------|
| **Confidence** | Confusion | Tooltips everywhere, clear labels, consistent patterns |
| **Trust** | Skepticism | Real-time data, visible activity indicators, audit trails |
| **Accomplishment** | Frustration | Progress indicators, celebration moments, quick wins |
| **Calm Focus** | Anxiety | Exception-first (hide noise), soft alerts, clean layouts |

### Emotion-to-Design Connections

| Emotional Goal | UX Design Approach |
|----------------|-------------------|
| **Empowered** | Exception-first dashboard—OPS chooses what to engage with |
| **Informed** | Proactive Telegram updates with visual receipt cards |
| **Calm** | Kanban board shows only exceptions, green = auto-handled |
| **Confident** | Every action shows loading → success → confirmation |
| **Trusted** | Real-time GPS updates, transparent status changes |
| **Accomplished** | Celebration micro-animations on milestone completions |

### Emotional Design Principles

1. **🧘 Calm Over Chaos:** Default state is GREEN/calm; exceptions demand attention through color/motion hierarchy
2. **💬 Proactive Over Reactive:** System speaks first—users never wonder "what's happening?"
3. **🎯 Clarity Over Cleverness:** Every element has ONE obvious purpose; no hidden functionality
4. **✨ Micro-Celebrations:** Small wins get small celebrations (subtle animations, check marks, progress rings)
5. **🛡️ Graceful Degradation:** Errors feel like guidance, not punishment; always show "what to do next"

---

## UX Pattern Analysis & Inspiration

### Inspiring Products Analysis

#### 1. Linear (Issue Tracking)
**Why It's Relevant:** Exception-first design, speed-obsessed, keyboard-first

| What They Do Well | PJL Connect Application |
|-------------------|------------------------|
| **Inbox Zero Philosophy** — Shows only what needs action | Exception board surfaces problems, hides routine |
| **Instant Keyboard Shortcuts** — Power users fly | OPS can rapidly triage exceptions without mouse |
| **Status Pills** — Visual, colorful, scannable | Shipment status as color-coded pills |
| **Optimistic UI** — Actions feel instant | Kanban card moves before server confirms |

#### 2. Notion (Kanban + Real-time)
**Why It's Relevant:** Drag-drop Kanban, real-time collaboration, clean aesthetics

| What They Do Well | PJL Connect Application |
|-------------------|------------------------|
| **Drag-Drop Kanban** — Satisfying card movement | Shipment cards drag between stages |
| **Real-time Updates** — See others' changes live | Livewire pushes GPS/status changes to all viewers |
| **Database Views** — Same data, multiple views | Table, Kanban, Map views of shipments |
| **Inline Editing** — Click-to-edit everything | Update shipment details without modal popups |

#### 3. Telegram Bot UI Patterns
**Why It's Relevant:** Your users already live here

| What They Do Well | PJL Connect Application |
|-------------------|------------------------|
| **Inline Buttons** — Tap, don't type | Driver confirms pickup with 1 button tap |
| **Visual Cards** — Rich message formatting | Shipment receipts as premium visual cards |
| **Conversation Threading** — Context preserved | Each shipment = one thread |
| **Quick Replies** — Pre-defined response options | Carrier accepts/declines with preset buttons |

#### 4. Uber Fleet Dashboard
**Why It's Relevant:** Logistics real-time tracking, exception handling

| What They Do Well | PJL Connect Application |
|-------------------|------------------------|
| **Live Map View** — All vehicles at a glance | GPS dots on Cambodia map |
| **Exception Bubbles** — Problems surface visually | Delayed shipments pulse/glow on map |
| **Driver Cards** — Tap marker → See details | Click GPS dot → Shipment detail panel |
| **Timeline View** — Chronological journey | Shipment events as vertical timeline |

### Transferable UX Patterns

#### Navigation Patterns
| Pattern | Source | PJL Connect Use |
|---------|--------|-----------------||
| **Exception Inbox** | Linear | Main OPS dashboard—show only what needs human attention |
| **Multi-View Switching** | Notion | Toggle between Kanban / Table / Map of same data |
| **Persistent Sidebar** | All modern SaaS | Navigation always visible, collapsible on mobile |

#### Interaction Patterns
| Pattern | Source | PJL Connect Use |
|---------|--------|-----------------||
| **Optimistic UI** | Linear | Card moves instantly, reverts if server fails |
| **Inline Editing** | Notion | Click any field to edit without modals |
| **Keyboard Shortcuts** | Linear | Power OPS can work without mouse |
| **Pull-to-Refresh** | Mobile apps | Mobile Back-Office gesture support |

#### Visual Patterns
| Pattern | Source | PJL Connect Use |
|---------|--------|-----------------||
| **Status Pills** | Linear/Notion | Colorful, semantic badges for shipment states |
| **Card-Based UI** | All modern SaaS | Shipments as scannable cards, not table rows |
| **Progress Rings** | Uber | Visual completion indicators |
| **Live Indicators** | Slack/Notion | Green dot = online, pulse = activity |

### Anti-Patterns to Avoid

| ❌ Anti-Pattern | Why It Fails | PJL Connect Avoidance |
|-----------------|--------------|----------------------|
| **Excel-Style Infinite Tables** | Overwhelming, no hierarchy | Card-based + exception-first |
| **Nested Menu Hell (SAP-style)** | Lost in navigation | Flat sidebar, max 2 levels deep |
| **Modal Overload** | Flow interruption, context loss | Slide-over panels, inline editing |
| **Silent Actions** | User anxiety—"did it work?" | Always show loading → success states |
| **Dense Data Walls** | Cognitive overload | Progressive disclosure, summary first |
| **Hover-Only Tooltips on Mobile** | Touch devices can't hover | Always-visible labels OR tap-to-reveal |

### Design Inspiration Strategy

#### ✅ Adopt Directly
| Pattern | Why |
|---------|-----|
| **Linear's Exception Inbox** | Core to our "humans handle 1%" philosophy |
| **Notion's Optimistic UI** | Speed is trust—no waiting for servers |
| **Telegram's Inline Buttons** | Users already know this interaction model |

#### 🔄 Adapt for PJL Connect
| Pattern | Adaptation Needed |
|---------|------------------|
| **Uber's Live Map** | Simplify for Cambodia region, fewer vehicles |
| **Notion's Kanban** | Add logistics-specific columns (Pickup → Transit → Customs → Delivered) |
| **Linear's Keyboard Shortcuts** | Start with 5 core shortcuts, expand based on usage |

#### ❌ Avoid Completely
| Anti-Pattern | Reason |
|--------------|--------|
| **Excel-style data entry** | This IS the problem we're solving |
| **ERP-style nested menus** | Creates the confusion we want to eliminate |
| **Desktop-only features** | Breaks our responsive parity principle |

---

## Design System Foundation

### Design System Choice

**Selected:** Tailwind CSS + Custom Blade Components

**Stack Integration:**
| Layer | Technology |
|-------|------------|
| **CSS Framework** | Tailwind CSS 3.x (ships with Laravel 11) |
| **Component Layer** | Custom Blade Components |
| **Interactivity** | Alpine.js (micro-interactions) |
| **Real-time** | Livewire 3.x (server-driven reactivity) |

### Rationale for Selection

| Factor | Decision |
|--------|----------|
| **Cost** | Free—no paid component libraries |
| **Laravel Integration** | Native—Tailwind ships with Laravel 11 |
| **Simplicity** | Utility-first approach, minimal abstraction |
| **Control** | Full ownership of component design |
| **Team Learning** | Standard Tailwind—widely documented |

### Implementation Approach

**Philosophy: Simple > Clever**

| Principle | Application |
|-----------|-------------|
| **Minimal Customization** | Use Tailwind defaults; only customize brand colors |
| **Copy-Paste First** | Start with simple patterns, refactor later if needed |
| **Blade First** | Use Blade components for static UI |
| **Livewire for State** | Reserve Livewire for real-time/stateful components |

### Customization Strategy

**1. Color Palette Only**
```js
// tailwind.config.js
colors: {
  'deep-teal': '#1E5A6B',
  'sky-blue': '#5BC0DE',
  // Keep all other Tailwind defaults
}
```

**2. Component Library Structure**
```
resources/views/components/
├── ui/           # Atoms: button, badge, card, input
├── layout/       # sidebar, header, footer
├── shipment/     # domain-specific: shipment-card, status-pill
└── dashboard/    # kanban-column, exception-card
```

**3. Styling Rules**
| Rule | Why |
|------|-----|
| Use Tailwind utility classes directly | Faster development, less abstraction |
| Extract to `@apply` only for 5+ repeated patterns | Keep CSS file small |
| Avoid custom CSS unless necessary | Maintainability |
| Dark mode via Tailwind's `dark:` variant | Built-in, free |

---

## Defining Core Experience

**Product Tagline:** *"Make handling logistics intuitive and seamless"*

### Dual Defining Experiences

PJL Connect delivers TWO interconnected defining experiences that work in harmony:

| Side | Defining Experience | User Feels |
|------|---------------------|------------|
| **Internal (OPS)** | "I look at my board and only see what needs my attention" | Empowered, calm, in control |
| **External (Customer)** | "I never have to ask—it just tells me" | Informed, respected, trusting |

**The Symbiosis:** When OPS handles exceptions efficiently (internal), customers receive proactive updates (external). Both experiences reinforce each other.

### User Mental Models

#### OPS Mental Model
| Current Reality | PJL Connect Shift |
|-----------------|-------------------|
| "I check everything manually" | "The board shows me what to check" |
| "I chase status updates" | "Updates come to me" |
| "I'm reactive to problems" | "I'm proactive with solutions" |

#### Customer Mental Model
| Current Reality | PJL Connect Shift |
|-----------------|-------------------|
| "I call to ask where my shipment is" | "I get told before I wonder" |
| "I don't trust ETAs" | "Updates are real-time and accurate" |
| "I feel in the dark" | "I feel informed every step" |

### Success Criteria

#### OPS Success Moments
| Criteria | Indicator |
|----------|----------|
| **Glance-ability** | Dashboard scanned in < 3 seconds |
| **Exception Clarity** | Problem + suggested action visible immediately |
| **Action Speed** | Most exceptions resolved in < 30 seconds |
| **Confidence** | Zero "did it work?" moments |

#### Customer Success Moments
| Criteria | Indicator |
|----------|----------|
| **Proactivity** | Updates arrive BEFORE customer wonders |
| **Clarity** | Status understood in one glance at Telegram card |
| **Trust** | Customer stops calling to check on shipments |
| **Delight** | Customer shares "this is how it should be" |

### Novel vs. Established Patterns

| Pattern Type | Application |
|--------------|-------------|
| **Established: Kanban** | Shipment stages as columns (familiar to users) |
| **Established: Exception-first** | Red/orange highlight for problems (Linear-style) |
| **Established: Telegram bots** | Inline buttons (users already know this) |
| **Novel: Dual-sided harmony** | OPS actions trigger customer updates automatically |

### Experience Mechanics

#### OPS Exception Flow
```
1. INITIATION: OPS opens dashboard
   └── Exception count badge shows "3 need attention"

2. INTERACTION: OPS clicks exception card
   └── Slide-over panel shows problem + AI suggestion + action buttons

3. FEEDBACK: OPS clicks "Confirm Solution"
   └── Card turns green, success toast, moves to resolved column

4. COMPLETION: Exception count decrements
   └── Customer automatically notified of resolution
```

#### Customer Update Flow
```
1. INITIATION: Shipment status changes (driver GPS, customs clear)
   └── System generates update automatically

2. INTERACTION: Customer receives Telegram message
   └── Visual card with status, location, next milestone

3. FEEDBACK: Customer taps "View Details"
   └── Full timeline opens in-bot

4. COMPLETION: Customer knows status without calling
   └── Trust in system increases
```

---

## Visual Design Foundation

### Color System

**Brand Colors**
| Token | Hex | Usage |
|-------|-----|-------|
| `deep-teal` | `#1E5A6B` | Headers, primary buttons, sidebar |
| `sky-blue` | `#5BC0DE` | Links, highlights, accent elements |

**Semantic Colors (Tailwind Defaults)**
| Token | Usage |
|-------|-------|
| `green-500` | Success states, resolved exceptions |
| `yellow-500` | Warnings, delayed shipments |
| `red-500` | Errors, critical exceptions |
| `gray-*` | Text hierarchy, borders, backgrounds |

**Exception Status Colors**
| Status | Color | Visual |
|--------|-------|--------|
| Critical | `red-500` | Pulsing badge |
| Warning | `yellow-500` | Static badge |
| Info | `sky-blue` | Subtle indicator |
| Resolved | `green-500` | Checkmark |

### Typography System

**Font Stack**
| Role | Font | Tailwind Class |
|------|------|----------------|
| **Primary** | Inter | `font-sans` |
| **Monospace** | System mono | `font-mono` |

**Type Scale**
| Element | Size | Weight | Usage |
|---------|------|--------|-------|
| `h1` | 2rem | Bold | Page titles |
| `h2` | 1.5rem | Semibold | Section headers |
| `h3` | 1.25rem | Medium | Card titles |
| `body` | 1rem | Regular | Default text |
| `small` | 0.875rem | Regular | Metadata, captions |
| `mono` | 0.875rem | Regular | Shipment IDs, codes |

### Spacing & Layout Foundation

**Spacing Scale (8px base)**
| Token | Value | Usage |
|-------|-------|-------|
| `space-1` | 4px | Tight gaps |
| `space-2` | 8px | Default element spacing |
| `space-3` | 12px | Card padding |
| `space-4` | 16px | Section spacing |
| `space-6` | 24px | Major section gaps |
| `space-8` | 32px | Page margins |

**Layout Grid**
| Context | Grid | Columns |
|---------|------|--------|
| Back-Office Desktop | 12-column | Sidebar (2) + Content (10) |
| Back-Office Mobile | Stack | Full width |
| Kanban Board | Flex | Auto-fit columns |

**Layout Density: Balanced**
- Not too dense (avoid cognitive overload)
- Not too airy (respect OPS efficiency needs)
- Cards with `p-4`, comfortable click targets

### Accessibility Considerations

| Requirement | Implementation |
|-------------|---------------|
| **Contrast** | All text meets WCAG AA (4.5:1 ratio) |
| **Focus States** | Visible focus rings on all interactive elements |
| **Touch Targets** | Minimum 44x44px for mobile |
| **Color Independence** | Never rely on color alone for meaning |

**Dark Mode:** Light mode only for MVP (can add later via Tailwind `dark:` variants)

---

## Design Direction Decision

### Design Directions Explored

We explored three distinct visual directions presented in an interactive HTML showcase:
1. **Clean Operations:** High-contrast, sharp boundaries, focused on data-heavy efficiency.
2. **Modern Logistics:** Softer edges, approachable SaaS aesthetic, lively status indicators.
3. **Focus First:** Dark-themed "mission control" vibe (discarded for MVP).

### Chosen Direction: The Hybrid Approach

A hybrid visual strategy was selected to balance different user needs across the platform:

| Application Area | Chosen Direction | Visual Philosophy |
|------------------|------------------|-------------------|
| **Job Menus / Shipments** | **Clean Operations (Option 1)** | High-contrast, sharp hierarchy, and maximum visibility for complex data sets. |
| **OPS Dashboard** | **Modern Logistics (Option 2)** | Friendly, professional SaaS feel with soft edges and pulsing status indicators for high-level monitoring. |

### Design Rationale

- **Context-Specific UI:** Data-intensive tasks (Jobs) benefit from the stark clarity of *Clean Operations*, while summary/monitoring tasks (Dashboard) benefit from the modern, approachable feel of *Modern Logistics*.
- **Visual Distinction:** Separating the "working" views from the "monitoring" views helps users mentally switch modes.
- **Brand Versatility:** Both options successfully utilize the *Deep Teal* and *Sky Blue* foundation while proving the system's flexibility.

### Implementation Approach

- **Consistency:** Use fixed layout patterns (sidebar, header) consistently across both themes.
- **Component Styling:** 
  - Table-heavy views (Jobs/Shipments) will use sharper headers and borders.
  - Dashboard widgets will use softer rounded corners (`rounded-2xl` or `3xl`) and subtle shadows.
- **Color Usage:** Deep Teal (#1E5A6B) remains the dominant structural color, while Sky Blue (#5BC0DE) is used more liberally in the Dashboard for a "lively" feel.

---

## Component Strategy

### Design System Foundation

**Primary Template:** TailAdmin Laravel (Free Edition)
- **License:** MIT (Free & Open Source)
- **Stack:** Laravel 11 + Tailwind CSS + Alpine.js
- **Features:** 500+ components, 7 dashboard variations, ApexCharts
- **Source:** https://tailadmin.com

**Supplementary Libraries:**
| Library | Purpose | License |
|---------|---------|---------|
| **Filament Kanban** | Drag-drop Kanban board for Jobs | MIT |
| **Flowbite** | Additional free UI components | MIT |
| **Leaflet.js** | GPS tracking map with geofences | BSD-2 |
| **PDF.js** | Document viewer in compliance tab | Apache 2.0 |
| **ApexCharts** | Dashboard charts (included in TailAdmin) | MIT |

---

### Components from Template (No Custom Code)

| Component Need | Source | Notes |
|----------------|--------|-------|
| Dashboard Widgets | TailAdmin | Stat cards, sparklines, charts |
| Tables + Filters | TailAdmin | Booking list, invoice list |
| Forms | TailAdmin | All input types, validation styles |
| Modals | TailAdmin | Carrier assignment, confirmations |
| Slide-overs | TailAdmin | Job detail panel, booking detail |
| Tabs | TailAdmin | Document/Tracking/Compliance tabs |
| Badges/Pills | TailAdmin | Status indicators, alerts |
| Kanban Board | Filament Kanban | Drag-drop Job management |
| Charts | ApexCharts | Volume trends, margin charts |
| GPS Map | Leaflet.js | Driver tracking, geofence display |

---

### Custom Components (Light Customization)

Only 3 components require light customization:

#### 1. Exception Card
**Base:** TailAdmin alert/card component
**Customization:**
- Add red vertical accent bar for urgency
- Include AI suggestion section
- Action buttons: Confirm / Override / Escalate

#### 2. Status Sync Badge
**Base:** TailAdmin badge component
**Customization:**
- Add sync icon indicator
- Tooltip showing Job ↔ Booking relationship
- Color matches Kanban column state

#### 3. Copy-Paste Magic Block
**Base:** TailAdmin code/pre component
**Customization:**
- Pre-formatted text block with copy button
- One-click clipboard copy
- Visual feedback on copy success

---

### Implementation Roadmap

**Phase 1 — Core Framework (Week 1-2)**
- Install TailAdmin Laravel template
- Configure Tailwind with PJL color tokens
- Setup authentication (Jetstream/Breeze)

**Phase 2 — Dashboard & Navigation (Week 2-3)**
- Implement app shell layout
- Create Dashboard widgets (using TailAdmin)
- Setup sidebar navigation

**Phase 3 — Jobs & Kanban (Week 3-4)**
- Integrate Filament Kanban package
- Customize Job card component
- Build Exception Terminal view

**Phase 4 — Bookings & Financial (Week 4-5)**
- Build Booking list/detail (TailAdmin tables)
- Create Invoice generator
- Implement Cost Entry forms

**Phase 5 — Tracking & Maps (Week 5-6)**
- Integrate Leaflet.js for GPS map
- Build geofence visualization
- Implement driver location polling


---

## UX Consistency Patterns

### Action Button Hierarchy

| Type | Usage | Tailwind Classes | Visual |
|------|-------|------------------|--------|
| **Primary** | Main action (Approve, Submit, Assign Carrier) | `bg-teal-700 hover:bg-teal-800 text-white` | Solid teal |
| **Secondary** | Alternative action (Edit, Cancel, View Details) | `border-teal-700 text-teal-700 hover:bg-teal-50` | Teal outline |
| **Danger** | Destructive (Reject, Delete, Escalate) | `bg-red-600 hover:bg-red-700 text-white` | Solid red |
| **Success** | Confirm positive (Confirm Solution, Mark Paid) | `bg-green-600 hover:bg-green-700 text-white` | Solid green |
| **Ghost** | Tertiary actions (Read More, Collapse) | `text-gray-600 hover:text-teal-700` | Text only |

**Button Placement Rules:**
- Primary action always **rightmost** in button groups
- Danger actions require confirmation modal
- Mobile: Full-width buttons in forms

---

### Feedback Patterns

| Event | Pattern | Duration | Position |
|-------|---------|----------|----------|
| **Success** | Toast notification | Auto-dismiss 3s | Top-right |
| **Error** | Toast notification | Persistent (manual dismiss) | Top-right |
| **Warning** | Inline banner | Persistent until action | Above affected content |
| **Info** | Toast OR inline | Auto-dismiss 5s | Top-right OR inline |
| **Loading** | Button spinner + disabled state | Until complete | Within action button |
| **Skeleton** | Animated placeholder | Until data loads | In place of content |

**Toast Component:**`n- Max 3 toasts visible at once
- Stack vertically, newest on top
- Include action link when applicable (e.g., 'View Job')

---

### Exception Visual Patterns

| Severity | Left Border | Badge Color | Animation | Icon |
|----------|-------------|-------------|-----------|------|
| **Critical** | `border-l-4 border-red-500` | Red | Pulse | ⚠️ |
| **Warning** | `border-l-4 border-yellow-500` | Yellow | None | ⏰ |
| **Info** | `border-l-4 border-blue-500` | Blue | None | ℹ️ |
| **Resolved** | `border-l-4 border-green-500` | Green | None | ✅ |

**Exception Card Layout:**
1. Severity indicator (colored border + badge)
2. Problem description (bold)
3. AI suggested solution (gray background block)
4. Action buttons: [Confirm] [Override] [Escalate]

---

### Navigation Patterns

**Sidebar Navigation:**
- Collapsible on mobile (hamburger menu)
- Active state: Teal background + white text
- Hover state: Light teal background
- Icons + text labels (icons only when collapsed)

**Breadcrumbs:**
- Show for all detail views (Job Detail, Booking Detail)
- Format: `Dashboard > Jobs > #PJL-0001`
- Clickable ancestors, current page not linked

**Tabs:**
- Use for Job/Booking detail panels (Documents, Tracking, Compliance, Financials, History)
- Active tab: Teal underline + bold text
- Horizontal scroll on mobile if > 4 tabs

---

### Form Patterns

**Validation:**
- Real-time validation on blur
- Error messages below field in red
- Invalid fields: Red border + error icon
- Valid fields: Green border + checkmark (optional)

**Required Fields:**
- Asterisk (*) after label
- All required fields grouped at top

**Form Layout:**
- Single column on mobile
- 2-column grid on desktop for related fields
- Action buttons at bottom, right-aligned

---

### Loading and Empty States

**Loading States:**
| Context | Pattern |
|---------|---------|
| Page load | Skeleton loader (TailAdmin built-in) |
| Button action | Spinner inside button, disabled state |
| Data refresh | Subtle overlay + spinner |
| Kanban drag | Optimistic update, rollback on error |

**Empty States:**
| Context | Message | Action |
|---------|---------|--------|
| No jobs | 'No jobs yet. Create your first booking!' | Link to /bookings |
| No exceptions | '✨ All clear! No exceptions to handle.' | None |
| No search results | 'No results for [query]. Try adjusting filters.' | Clear filters button |
| No carriers | 'No carriers available. Add one in Settings.' | Link to /settings |


---

## Responsive Design & Accessibility

### Responsive Strategy

**Platform-Specific Approach:**

| Platform | Strategy | Rationale |
|----------|----------|-----------|
| **Back-Office (Web)** | Desktop-first | OPS team works primarily on desktop; mobile is secondary access |
| **Telegram Bots** | Native mobile | Telegram handles responsive; we design cards/buttons for phone screens |

---

### Breakpoint Strategy (Tailwind)

| Breakpoint | Size | Layout Changes |
|------------|------|----------------|
| **sm** | 640px | Sidebar collapses to hamburger menu |
| **md** | 768px | Tables become scrollable cards on mobile |
| **lg** | 1024px | Full sidebar visible, standard layout |
| **xl** | 1280px | Expanded Kanban columns, more data density |
| **2xl** | 1536px | Maximum data density for large monitors |

---

### Component Adaptations

| Component | Desktop (lg+) | Tablet (md) | Mobile (sm) |
|-----------|---------------|-------------|-------------|
| **Sidebar** | Always visible, expanded | Collapsed icons | Hamburger menu |
| **Kanban Board** | All columns visible | Horizontal scroll | Swipe navigation |
| **Job Detail** | Slide-over panel (40% width) | Slide-over (60%) | Full-screen modal |
| **Tables** | Full columns visible | Key columns only | Card view |
| **Dashboard** | 4-column widget grid | 2-column grid | Single column stack |
| **Forms** | 2-column layout | 2-column | Single column |

---

### Accessibility Strategy

**Target Compliance:** WCAG 2.1 Level AA

| Requirement | Implementation | Status |
|-------------|----------------|--------|
| **Color Contrast** | 4.5:1 minimum (Deep Teal #1E5A6B on white = 5.2:1) | ✅ Compliant |
| **Keyboard Navigation** | All interactive elements focusable with Tab key | Required |
| **Focus Indicators** | Visible teal outline (`focus:ring-2 focus:ring-teal-500`) | Required |
| **Touch Targets** | Minimum 44x44px for mobile buttons | Required |
| **Screen Readers** | ARIA labels on icons, dynamic content, modals | Required |
| **Error Identification** | Errors announced via aria-live + visual indicator | Required |
| **Skip Links** | 'Skip to main content' link for keyboard users | Required |
| **Alt Text** | Descriptive alt text for all images | Required |

---

### Accessibility Implementation Checklist

**Semantic HTML:**
- [ ] Use `<main>`, `<nav>`, `<header>`, `<footer>` landmarks
- [ ] Use heading hierarchy (h1 > h2 > h3) correctly
- [ ] Use `<button>` for actions, `<a>` for navigation

**ARIA Labels:**
- [ ] Label all icon-only buttons (`aria-label='Close'`)
- [ ] Use `aria-describedby` for form field hints
- [ ] Mark live regions with `aria-live='polite'` for toasts

**Keyboard:**
- [ ] All modals trap focus inside until closed
- [ ] Escape key closes modals/slide-overs
- [ ] Enter/Space activate buttons
- [ ] Arrow keys navigate Kanban cards

---

### Testing Strategy

| Test Type | Tool | Frequency |
|-----------|------|-----------|
| **Automated Audit** | Axe DevTools, Lighthouse | Every PR |
| **Screen Reader** | NVDA (Windows), VoiceOver (Mac/iOS) | Before release |
| **Keyboard Navigation** | Manual testing | Every feature |
| **Color Contrast** | WebAIM Contrast Checker | Design phase |
| **Color Blindness** | Sim Daltonism, Coblis | Before release |
| **Responsive** | Chrome DevTools device mode | Every feature |

---

### Dark Mode

**Status:** Not in MVP scope

**Future Implementation:**
- Tailwind `dark:` variants ready for use
- Color tokens defined for dark mode adaptation
- User preference detection via `prefers-color-scheme`

