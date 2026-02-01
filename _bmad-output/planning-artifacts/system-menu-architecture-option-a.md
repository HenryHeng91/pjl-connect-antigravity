# PJL Connect - System Menu Architecture

## Option A: Hierarchical Text Tree with Laravel Components & AI Agent Comments

> **AI Agent Instructions:** This document defines the complete menu structure for PJL Connect.
> Each menu item includes the Blade component tag to use and implementation notes.
> Follow the component hierarchy strictly. All components live in `resources/views/components/`.

---

## Core Concept: Booking vs. Job

> **CRITICAL for AI Agents and Developers**

### Booking (Customer-Facing Order Record)
- **Purpose:** The shipment order that customer and OPS reference
- **Visibility:** Customer sees in `/track`, OPS sees in Bookings menu
- **Contains:** Container numbers, documents, route, customer info, overall status
- **Booking ID:** Customer reference number (e.g., `#PJL-2026-0001`)
- **Location:** Bookings Menu (list/table view)
- **Database:** `bookings` table

### Job (Internal OPS Task)
- **Purpose:** The operational work item for OPS to execute
- **Visibility:** OPS only (Kanban board)
- **Contains:** Same shipment but as a drag-drop task card
- **Location:** Jobs Menu (Kanban view)
- **Database:** `jobs` table (foreign key: `booking_id`)

### Relationship: 1:1 Sync
- Booking and Job are **created together** when OPS approves a booking request
- Job status and Booking status stay **in sync**
- When OPS drags Job card → Booking status auto-updates → Customer sees change in `/track`

---

## Module 1: PJL Connect Back-Office (Web App)

```
📊 DASHBOARD
│   Route: /dashboard
│   Layout: <x-layout.app-shell>
│   Design Direction: "Modern Logistics" (Option 2) - soft, friendly, lively
│
├── Pulse Snapshot
│   │   Component: <x-dashboard.pulse-snapshot>
│   │   AI Note: Real-time widget showing active jobs, pending assignments, exception count
│   │   Data Source: Job::whereIn('status', ['active', 'pending'])->count()
│   │   Update: Livewire polling every 30s
│   │
│   ├── Active Jobs Counter
│   │       Component: <x-dashboard.stat-card variant="active">
│   │       AI Note: Green background, show count + trend arrow
│   │
│   ├── Pending Assignments Counter
│   │       Component: <x-dashboard.stat-card variant="warning">
│   │       AI Note: Yellow background if > 0, link to Kanban "New" column
│   │
│   └── Exception Alerts Counter
│           Component: <x-dashboard.stat-card variant="critical">
│           AI Note: Red pulsing badge if > 0, click → Exception Terminal
│
├── Volume Counter
│   │   Component: <x-dashboard.volume-counter>
│   │   AI Note: Daily/weekly shipment counts with sparkline chart
│   │
│   └── Period Selector
│           Component: <x-ui.date-range-picker>
│           AI Note: Presets: Today, This Week, This Month, Custom
│
├── Team Workload View
│   │   Component: <x-dashboard.team-workload>
│   │   AI Note: Bar chart showing jobs per OPS member
│   │   Data Source: Job::groupBy('assigned_ops_id')->count()
│   │
│   └── OPS Member Cards
│           Component: <x-dashboard.ops-card>
│           AI Note: Avatar, name, active job count, click → filter Kanban by OPS
│
└── Management Summary Panel
        Component: <x-dashboard.management-summary>
        AI Note: For CEO/Management role only (RBAC gate)
        Shows: Active volume, completed this week, revenue snapshot
        Design: Expandable accordion, collapsed by default for OPS

---

📦 JOBS
│   Route: /jobs
│   Layout: <x-layout.app-shell>
│   Design Direction: "Clean Operations" (Option 1) - sharp, data-dense
│
├── Job Kanban Board
│   │   Component: <x-jobs.kanban-board>
│   │   AI Note: CORE FEATURE - Drag-drop columns, Livewire for real-time updates
│   │   Columns: New → Assigned → Pickup → In Transit → Customs → Delivered → Closed
│   │   Card Component: <x-jobs.kanban-card>
│   │
│   ├── Column: New
│   │       AI Note: Jobs from bot bookings land here. Exception indicator if OCR low-confidence.
│   │
│   ├── Column: Assigned
│   │       AI Note: Carrier accepted but driver not yet en route.
│   │
│   ├── Column: Pickup
│   │       AI Note: Driver dispatched, waiting for arrival confirmation.
│   │
│   ├── Column: In Transit
│   │       AI Note: Show live GPS indicator, ETA countdown.
│   │
│   ├── Column: Customs
│   │       AI Note: For international jobs. Show lane status: Red/Yellow/Green/Blue.
│   │
│   ├── Column: Delivered
│   │       AI Note: Pending financial close. Show "Generate Invoice" action.
│   │
│   └── Column: Closed
│           AI Note: Archived. Searchable but hidden by default.
│
├── Job Detail View
│   │   Component: <x-jobs.detail-panel> (slide-over)
│   │   AI Note: Opens as side panel, not full page navigation
│   │
│   ├── Header Section
│   │       Component: <x-jobs.detail-header>
│   │       Shows: Job ID, Customer, Status Pill, Timeline progress bar
│   │
│   ├── Documents Tab
│   │       Component: <x-jobs.documents-viewer>
│   │       AI Note: PDF viewer with OCR overlay highlighting
│   │       Actions: Download, Print, Annotate, Request Missing Doc
│   │
│   ├── Tracking Tab
│   │       Component: <x-jobs.tracking-map>
│   │       AI Note: Leaflet/Google Maps with driver GPS dot, geofence polygons
│   │       Shows: Current location, route, ETA, stop history
│   │
│   ├── Compliance Tab (Growth)
│   │       Component: <x-jobs.compliance-console>
│   │       AI Note: Copy-Paste Magic blocks for ASYCUDA
│   │
│   ├── Financials Tab
│   │       Component: <x-jobs.financials-tab>
│   │       Shows: Cost entries, customer rate, margin preview
│   │       Actions: Add Cost, Generate Invoice
│   │
│   └── History Pane
│           Component: <x-jobs.audit-trail>
│           AI Note: Chronological log of all actions with timestamps and actors
│
├── Exception Terminal
│   │   Component: <x-jobs.exception-terminal>
│   │   AI Note: CRITICAL - List of all jobs with exceptions needing OPS action
│   │   Filter: status = 'exception' OR silence_escalation = true
│   │
│   └── Exception Card
│           Component: <x-jobs.exception-card>
│           Shows: Problem description, AI suggested resolution, action buttons
│           Actions: "Confirm Solution", "Override", "Escalate to Manager"
│
├── Carrier Assignment Panel
│   │   Component: <x-jobs.carrier-assignment> (modal)
│   │   AI Note: Shows available carriers sorted by rate/reliability score
│   │
│   ├── Carrier List
│   │       Component: <x-jobs.carrier-list-item>
│   │       Shows: Name, rate, reliability %, last job date
│   │
│   └── Pickup Time Selector
│           Component: <x-ui.datetime-picker>
│           AI Note: Default to next available slot based on carrier preferences
│
└── Nag-Monitor
        Component: <x-jobs.nag-monitor>
        AI Note: Shows carriers who haven't accepted within threshold
        Actions: "Send Reminder", "Reassign", "Call Carrier"

---

📝 BOOKINGS
│   Route: /bookings
│   Layout: <x-layout.app-shell>
│   Design Direction: "Clean Operations" (Option 1)
│   AI Note: Customer-facing order records. 1:1 sync with Jobs.
│
├── Booking List
│   │   Component: <x-bookings.list-table>
│   │   AI Note: Filterable table showing all bookings
│   │   Columns: Booking ID, Customer, Service, Status, Created, Actions
│   │
│   ├── Status Filter
│   │       Component: <x-ui.filter-pills>
│   │       Options: All, New (awaiting review), Active, Completed
│   │       AI Note: Status synced from linked Job's Kanban column
│   │
│   └── Quick Search
│           Component: <x-ui.search-input>
│           AI Note: Search by booking ID, customer name, container number
│
├── Booking Detail
│   │   Component: <x-bookings.detail-view>
│   │   AI Note: Full shipment data display - the customer record of truth
│   │
│   ├── Booking Info Section
│   │       Component: <x-bookings.info-grid>
│   │       Shows: Container#, Route, Customer, Service Type
│   │       AI Note: All shipment details live here (documents, dates, etc.)
│   │
│   ├── Status Display
│   │       Component: <x-bookings.status-sync>
│   │       AI Note: Shows status synced from linked Job's Kanban column
│   │       Visual: Status pill matching Job column name
│   │
│   ├── Document Attachments
│   │       Component: <x-bookings.document-list>
│   │       AI Note: Thumbnails of uploaded docs, click to preview
│   │       Actions: Request Missing Doc (sends to Customer Bot)
│   │
│   └── Linked Job
│           Component: <x-bookings.linked-job-card>
│           AI Note: Quick link to open the Job in Kanban view
│           Shows: Job status, assigned carrier, last activity
│           Action: "Open in Kanban" → navigates to Jobs menu with this job focused
│
├── Booking Request Queue
│   │   Component: <x-bookings.request-queue>
│   │   AI Note: Pending requests from Bot or manual entry awaiting approval
│   │
│   └── Request Actions
│           - "Approve" → Creates Booking + Job simultaneously (1:1)
│           - "Request More Info" → <x-ui.button> sends Bot message
│           - "Reject" → <x-ui.button variant="danger"> logs reason
│
└── Multi-Leg Builder (Growth)
        Component: <x-bookings.multi-leg-builder>
        AI Note: Visual journey builder: Truck → Port → Sea → Destination
        Each leg is a <x-bookings.leg-card> with its own carrier/timeline

---

💰 FINANCIAL
│   Route: /financial
│   Layout: <x-layout.app-shell>
│   Design Direction: "Clean Operations" (Option 1)
│
├── Financial Overview Dashboard
│   │   Component: <x-financial.overview>
│   │   Shows: Total revenue, costs, margins this period
│   │   Charts: Revenue trend, margin waterfall
│   │
│   └── Period Selector
│           Component: <x-ui.date-range-picker>
│
├── Invoice List
│   │   Component: <x-financial.invoice-list>
│   │   Columns: Invoice #, Customer, Amount, Status, Due Date, Actions
│   │   Status Pills: Draft (gray), Sent (blue), Paid (green), Overdue (red)
│   │
│   └── Invoice Actions
│           - "View" → <x-financial.invoice-detail> (slide-over)
│           - "Send" → Trigger email/Telegram notification
│           - "Mark Paid" → Status update with payment date
│
├── Invoice Generator
│   │   Component: <x-financial.invoice-generator>
│   │   AI Note: Pre-populates from job data. OPS reviews and confirms.
│   │   Fields: Customer, Line Items (from job costs), Taxes, Total
│   │
│   └── PDF Preview
│           Component: <x-financial.invoice-pdf-preview>
│           AI Note: Live preview with company letterhead template
│
├── Cost Entry
│   │   Component: <x-financial.cost-entry>
│   │   AI Note: OPS records vendor costs per job
│   │   Fields: Job ID, Vendor, Category, Amount, Receipt Upload
│   │
│   └── Receipt Scanner (Growth)
│           Component: <x-financial.receipt-scanner>
│           AI Note: OCR for expense receipts, auto-fill fields
│
└── Export
        Component: <x-financial.export-panel>
        AI Note: CSV/Excel export for QuickBooks sync
        Options: Date range, include line items, format selection

---

⚙️ CONFIGURATOR
│   Route: /settings
│   Layout: <x-layout.app-shell>
│   Design Direction: "Clean Operations" (Option 1)
│
├── Customer Database
│   │   Component: <x-configurator.customer-list>
│   │   AI Note: All registered customers with Telegram binding status
│   │
│   └── Customer Profile
│           Component: <x-configurator.customer-profile>
│           Fields: Company name, Contact, Telegram ID, Rate Card, Service agreements
│           AI Note: Rate Card = pricing matrix for this customer
│
├── Carrier Database
│   │   Component: <x-configurator.carrier-list>
│   │   AI Note: Fleet owners with driver roster
│   │
│   ├── Carrier Profile
│   │       Component: <x-configurator.carrier-profile>
│   │       Fields: Company, Contact, Negotiated rates, Reliability score
│   │
│   └── Driver Roster
│           Component: <x-configurator.driver-roster>
│           AI Note: Drivers bound to carrier via QR enrollment
│           Shows: Name, Telegram ID, Active job, Location status
│
├── Identity Center
│   │   Component: <x-configurator.identity-center>
│   │   AI Note: User management with RBAC
│   │
│   ├── User List
│   │       Component: <x-configurator.user-list>
│   │       Columns: Name, Email, Role, Status, Last Active
│   │
│   ├── Role Management
│   │       Component: <x-configurator.role-manager>
│   │       Roles: Admin, Manager, OPS, Broker, Accounting, ReadOnly
│   │
│   └── Telegram Binding
│           Component: <x-configurator.telegram-binding>
│           AI Note: Link Telegram ID to system user for bot access
│
├── Service Rules (Growth)
│   │   Component: <x-configurator.service-rules>
│   │   AI Note: Document requirements matrix by service/destination
│   │
│   └── Rule Editor
│           Component: <x-configurator.rule-editor>
│           AI Note: Define required docs per service type and route
│
└── Geofence Manager (Growth)
        Component: <x-configurator.geofence-manager>
        AI Note: Define pickup/delivery zones for auto-status triggers
        Map interface with polygon drawing tools

---

## Module 2: PJL Customer Bot (Telegram)

```
🤖 PJL CUSTOMER BOT
│   Bot Username: @PJLConnectBot
│   AI Note: Telegram Bot API, webhook to Laravel. Use Telegram\Bot\Api package.
│
├── /start
│   │   Handler: App\Telegram\Handlers\StartHandler
│   │   AI Note: First-time user registration + returning user welcome
│   │
│   ├── New User Flow
│   │       → Display welcome message
│   │       → Prompt: "Enter your company registration code"
│   │       → Validate code against customers.registration_code
│   │       → Bind telegram_id to customer record
│   │       → "Welcome [Company Name]! You're all set."
│   │
│   └── Returning User
│           → Lookup telegram_id in customers table
│           → "Welcome back, [Company Name]! How can I help?"
│           → Show inline keyboard: [📦 New Booking] [📍 Track] [❓ Help]
│
├── /new
│   │   Handler: App\Telegram\Handlers\NewBookingHandler
│   │   AI Note: CORE FLOW - Booking creation with OCR
│   │
│   ├── Step 1: Service Selection
│   │       → Send inline keyboard: [🚚 Truck] [🚢 Sea] [✈️ Air] [📋 Customs Only]
│   │       → Store selection in conversation state
│   │
│   ├── Step 2: Document Upload
│   │       → "Please upload your documents (Invoice, Packing List)"
│   │       → Accept: Photo, PDF, Excel
│   │       → Store file in S3, queue OCR job
│   │
│   ├── Step 3: OCR Processing
│   │       → Show "Processing..." animation (edit message)
│   │       → Call OCR Engine (Google Cloud Vision)
│   │       → Extract: Container#, Weight, Description, Origin, Destination
│   │
│   ├── Step 4: Confidence Check
│   │       → If confidence >= 85%: Show Visual Receipt Card
│   │       → If confidence < 85%: Prompt corrections
│   │           "I found Container# MSKU1234567. Is this correct?"
│   │           [✅ Yes] [✏️ Edit]
│   │
│   ├── Step 5: Visual Receipt Card
│   │       AI Note: Rich message with parsed data in formatted layout
│   │       Component: Telegram message with HTML formatting
│   │       Shows: Service, Container#, Route, Docs attached
│   │       Buttons: [✅ Confirm Booking] [✏️ Edit] [❌ Cancel]
│   │
│   └── Step 6: Confirmation
│           → Create booking record in database
│           → Notify OPS via Internal Bot
│           → "Booking #PJL-2026-0001 confirmed! We'll ping you when carrier is assigned."
│
├── /track
│   │   Handler: App\Telegram\Handlers\TrackHandler
│   │   AI Note: Show active bookings/jobs for this customer
│   │
│   ├── Active Jobs List
│   │       → Query jobs where customer_id = current AND status != 'closed'
│   │       → Send message with list: "Your active shipments:"
│   │       → Each job as inline button: [#PJL-0001 - In Transit]
│   │
│   ├── Job Detail (on tap)
│   │       → Show: Status, Current Location (if GPS available), ETA
│   │       → Map thumbnail if live tracking available
│   │       → Buttons: [🔄 Refresh] [📞 Contact OPS]
│   │
│   └── No Active Jobs
│           → "No active shipments. Need to book? Tap /new"
│
├── /docs (Growth)
│   │   Handler: App\Telegram\Handlers\DocsHandler
│   │   AI Note: Document upload decoupled from booking (for deadline reminders)
│   │
│   └── Deadline-Aware Upload
│           → "Which booking is this document for?"
│           → Show active bookings needing docs
│           → Upload → Attach to booking → "Document received! ✅"
│
└── /help
        Handler: App\Telegram\Handlers\HelpHandler
        AI Note: Escalate to human OPS
        → "Connecting you to our team..."
        → Forward message to OPS Internal Bot with customer context
        → "An OPS member will respond shortly."
```

---

## Module 3: PJL Carrier & Driver Bot (Telegram)

```
🚚 PJL CARRIER BOT
│   Bot Username: @PJLCarrierBot
│   AI Note: Separate bot for carriers/drivers. Same Laravel backend.
│
├── /enroll
│   │   Handler: App\Telegram\Handlers\EnrollHandler
│   │   AI Note: Carrier onboarding + driver binding
│   │
│   ├── Carrier Registration
│   │       → "Enter your carrier registration code"
│   │       → Validate against carriers.registration_code
│   │       → Bind telegram_id to carrier record
│   │       → "Welcome, [Carrier Name]! You can now receive job offers."
│   │
│   └── Driver Binding (QR Flow)
│           → Carrier admin generates QR in Back-Office
│           → Driver scans QR with Telegram camera
│           → QR contains: carrier_id + enrollment_token
│           → Driver's telegram_id bound to carrier
│           → "Driver [Name] enrolled under [Carrier]!"
│
├── /accept
│   │   Handler: App\Telegram\Handlers\AcceptHandler
│   │   AI Note: Job dispatch with Nag-Loop
│   │
│   ├── Job Offer Card
│   │       → System sends: "New job available!"
│   │       → Card shows: Pickup location, Destination, Pickup time, Rate
│   │       → Buttons: [✅ Accept] [❌ Decline] [📞 Call OPS]
│   │
│   ├── Nag-Loop (if no response)
│   │       AI Note: Repeat every 5 minutes until response or timeout
│   │       → 5 min: "Reminder: Job #PJL-0001 awaiting your response"
│   │       → 15 min: "Final reminder: Accept within 5 min or job reassigned"
│   │       → 20 min: Escalate to OPS Exception Terminal
│   │
│   ├── Accept Flow
│   │       → Update job: carrier_id, status = 'carrier_accepted'
│   │       → "Great! Job #PJL-0001 assigned. Driver assignment next."
│   │       → If carrier has 1 driver: Auto-assign
│   │       → If multiple drivers: Show driver selection keyboard
│   │
│   └── Decline Flow
│           → Log decline reason (optional prompt)
│           → Trigger OPS notification for reassignment
│           → "Job declined. Thanks for letting us know."
│
├── /location
│   │   Handler: App\Telegram\Handlers\LocationHandler
│   │   AI Note: Toggle native Telegram live location sharing
│   │
│   ├── Start Sharing
│   │       → Check if driver has active job
│   │       → "Please share your live location for job #PJL-0001"
│   │       → User taps Telegram's "Share Live Location" button
│   │       → Webhook receives location updates (Telegram handles this)
│   │       AI Note: Store in driver_locations table with timestamp
│   │
│   └── Stop Sharing
│           → Driver stops sharing OR job completed
│           → "Location sharing stopped. Safe travels!"
│
└── /status
        Handler: App\Telegram\Handlers\StatusHandler
        AI Note: Manual status override (backup for geofence failures)
        │
        ├── Status Options
        │       → Show inline keyboard: [📦 Arrived at Pickup] [🚚 Departed] [📍 Arrived at Destination] [✅ Delivered]
        │
        └── Status Update
                → Update job status in database
                → Trigger customer notification (proactive update)
                → Log in audit trail with "manual_override" flag
                AI Note: Prefer geofence auto-trigger; this is fallback
```

---

## Module 4: PJL OPS Internal Bot (Telegram Group)

```
📢 PJL OPS INTERNAL BOT
│   Bot Username: @PJLOpsBot
│   Context: Added to private Telegram group for OPS team
│   AI Note: One-way notifications + deep links to Back-Office
│
├── Real-time Alerts
│   │   AI Note: Triggered by system events, not user commands
│   │
│   ├── New Booking Alert
│   │       Trigger: Booking created via Customer Bot
│   │       Message: "📦 New Booking from [Customer]"
│   │       Details: Service, Container#, Docs
│   │       Button: [View in Back-Office] → deep link to /bookings/{id}
│   │
│   ├── Carrier Accepted Alert
│   │       Trigger: Carrier accepts job
│   │       Message: "✅ [Carrier] accepted Job #PJL-0001"
│   │       Button: [View Job] → deep link to /jobs/{id}
│   │
│   └── Driver Assigned Alert
│           Trigger: Driver bound to job, location sharing started
│           Message: "🚚 Driver [Name] en route for Job #PJL-0001"
│           Button: [Track Live] → deep link to /jobs/{id}/tracking
│
├── Exception Pings
│   │   AI Note: HIGH PRIORITY - These need OPS action
│   │
│   ├── Silence Escalation
│   │       Trigger: GPS blackout > 15 minutes
│   │       Message: "⚠️ SILENCE ALERT: Driver [Name] offline for 15 min"
│   │       Details: Last known location, job info
│   │       Buttons: [📞 Call Driver] [Open Exception Terminal]
│   │
│   ├── Carrier Non-Response
│   │       Trigger: Nag-Loop timeout (20 min no response)
│   │       Message: "⚠️ [Carrier] not responding to Job #PJL-0001"
│   │       Buttons: [Reassign Carrier] [Open Job]
│   │
│   └── OCR Low Confidence
│           Trigger: Booking created with OCR confidence < 70%
│           Message: "🔍 Manual review needed: Booking #PJL-0001"
│           Details: Low-confidence fields highlighted
│           Button: [Review Booking] → /bookings/{id}
│
└── Deep-Links
        AI Note: All buttons link to Back-Office with auto-authentication
        Format: https://app.pjlconnect.com/jobs/{id}?token={one_time_token}
        Token: Single-use JWT valid for 5 minutes
```

---

## Cross-Module Integration Notes

```
AI AGENT INTEGRATION INSTRUCTIONS:

1. EVENT BUS PATTERN
   - All status changes emit Laravel Events
   - Listeners handle: Bot notifications, Dashboard updates, Audit logging
   - Example: JobStatusChanged → NotifyCustomerBot, UpdateKanbanLivewire, LogAudit

2. LIVEWIRE REAL-TIME UPDATES
   - Dashboard widgets: Poll every 30s
   - Kanban board: Echo/Pusher for instant drag-drop sync
   - Exception Terminal: Echo for new exceptions

3. TELEGRAM WEBHOOK ARCHITECTURE
   - Single Laravel route: POST /telegram/webhook/{bot_type}
   - Middleware: Verify Telegram signature
   - Router: Route to appropriate Handler based on message type

4. COMPONENT ORGANIZATION
   resources/views/components/
   ├── ui/           # Generic: button, badge, card, input, modal
   ├── layout/       # app-shell, sidebar, header
   ├── dashboard/    # pulse-snapshot, stat-card, volume-counter
   ├── jobs/         # kanban-board, kanban-card, detail-panel
   ├── bookings/     # list-table, detail-view, ocr-data-grid
   ├── financial/    # invoice-list, cost-entry, export-panel
   └── configurator/ # customer-list, carrier-list, user-list

5. DESIGN DIRECTION MAPPING
   - Dashboard routes: "Modern Logistics" (rounded, soft, Sky Blue accents)
   - Job/Booking routes: "Clean Operations" (sharp, data-dense, Deep Teal headers)
   - Apply via Blade @props or conditional Tailwind classes
```
