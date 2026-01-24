---
stepsCompleted: [1, 2]
inputDocuments: ['_bmad-output/analysis/brainstorming-session-2026-01-23.md']
date: 2026-01-24
author: Antigravity
---

# Product Brief: PJL Connect

## Problem Statements

Current logistics operations are governed by **"Manual Chaos."** Fragmented Telegram chats, manual Excel entry, and constant phone-call follow-ups create significant friction. This lack of structure leads to:
- **Information Silos:** Critical data exists only in personal chat histories.
- **Inaccurate Documentation:** High error rates in manual SAD (Single Administrative Document) drafting cause expensive "Red Lane" inspections in ASYCUDA.
- **Financial Leakage:** Delayed invoicing and manual reimbursement tracking lead to untracked expenses and lost revenue.
- **Operational Blind Spots:** Drivers and carriers lack real-time accountability, and management lacks profit visibility.

## Goal

To build a **next-generation "Invisible" Multi-Modal Logistics Operating System** that modernizes manual operations across **Land, Sea, and Air freight**. The system will support complex, **multi-leg shipments** (e.g., Land transport to port + Sea freight) through automated communication, OCR-driven documentation, and real-time cross-modal tracking. The goal is to reach a **99% "Happy Path" automation rate**, where human OPS intervention is reserved only for exceptions.

## Feasibility Study

### Stakeholder Persona

This system serves a complex ecosystem of 7 distinct user groups, divided into Primary (Operational) and Secondary (Strategic/Support) users.

#### Primary Users (The Operational Core)

-   **Customer (Industrial/Logistics Managers):**
    -   **Context:** Managing high-volume import/export or regional distribution.
    -   **Motivation:** Zero-friction booking, 100% price certainty, and proactive visibility.
    -   **Pain Point:** Spent 40% of their day answering "Where is my truck?" and re-sending documents.
    -   **Success Vision:** A "Quiet Bot" that only pings when action is needed or a milestone is hit.
-   **OPS Personnel (The Supervisors):**
    -   **Context:** Back-office "Command Center" managing daily carrier assignments.
    -   **Motivation:** Focus on relationships and exceptions, not manual data entry.
    -   **Pain Point:** Copy-pasting from Telegram to Excel; carrier "chasing" fatigue.
    -   **Success Vision:** A Kanban dashboard where the system handles 90% of bookings automatically.
-   **Driver (The Executioners):**
    -   **Context:** Road-level execution, often external/gig-economy or sub-contracted.
    -   **Motivation:** Getting paid quickly, minimal paperwork, and clear directions.
    -   **Pain Point:** Phone-call harassment from OPS; manual check-ins in confusing apps.
    -   **Success Vision:** Passive location sharing (Telegram) and simple "Arrived/Delivered" buttons.
-   **Customs Broker (The Compliance Guards):**
    -   **Context:** Handling ASYCUDA SAD submissions and document verification.
    -   **Motivation:** Zero "Red Lane" inspections and 100% doc accuracy.
    -   **Pain Point:** Re-typing data from fuzzy PDFs into government portals.
    -   **Success Vision:** One-click review of AI-generated Draft SADs.

#### Secondary Users (Strategic & Support)

-   **Carrier (Company Owners/Dispatchers):**
    -   **Context:** Managing fleets of trucks or shipping line space.
    -   **Motivation:** High vehicle utilization and consistent booking volume.
    -   **Pain Point:** Missing booking alerts or delayed dispatch instructions.
-   **Accounting (The Auditors):**
    -   **Context:** Corporate finance and ledger management.
    -   **Motivation:** 100% data consistency between OPS and Finance.
    -   **Pain Point:** Disconnected OPS spreadsheets leading to reconciliation errors.
-   **Management (CEO / Strategic Owner):**
    -   **Context:** Business growth and performance analysis.
    -   **Motivation:** Real-time visibility into "Lane Velocity" and per-shipment profit margins.
    -   **Pain Point:** Waiting for monthly reports to see where the business is leaking profit.

### Current Business Process

- **Intake:** Customer messages an OPS person via Telegram -> OPS manually records info in Excel.
- **Assignment:** OPS calls/messages carriers to find availability -> Carriers call drivers.
- **Documentation:** Customer sends PDF/Images of Inv/PL -> Broker manually re-types data into ASYCUDA.
- **Tracking:** OPS calls driver every 2 hours -> OPS updates Customer manually.
- **Invoicing:** Accounting manually tallies receipts and Excel sheets at the end of the month.

### Proposed Business Process

1.  **Automated Intake (10 Steps):** Customer uses **PJL Bot** to upload files; AI extracts data and identifies **Multi-Modal needs** (e.g., Truck + Sea).
2.  **Smart Job Assignment (28 Steps):** OPS selects one or multiple Carriers for each **Leg** of the job. A "Nag-Bot" escalates pings to trucking sub-contractors or shipping line contacts.
3.  **Multi-Modal Tracking:** Native Telegram location sharing for land legs; API-based container tracking or vessel monitoring for sea/air legs.
4.  **Job-Level Compliance:** Once a job reaches "Waiting Customs Preparation," a dedicated Broker is assigned. They access the **Job Detail Console** to review customer documents side-by-side with AI-extracted data.
5.  **ASYCUDA "Copy-Paste Magic":** The system generates a formatted series of data blocks. The Broker uses a "One-Click Copy" function to quickly paste this data into the official ASYCUDA portal, eliminating manual typing.
6.  **Multi-Leg Job Financials:** OPS clicks `Finalize` once the entire multi-leg job is complete; system calculates multi-vendor costs vs. customer rates for one-click ledger sync.

### Research and Ideation on the solution from the market, best practices, and the best logistics system in the world

Inspired by industry leaders like **Grab**, **Foodpanda**, and **Global 3PL providers**:
- **"Invisible App" Philosophy:** No new app installation for drivers/customers; leverage existing Telegram behavior.
- **Geofenced Milestones:** Automatic status updates based on location stream (best-in-class tracking).
- **Chaos Engineering Fallbacks:** Moving from complex automated sensors to a **"Resilient Human-in-the-Loop"** model (e.g., manual OPS pin override if GPS fails).
- **50 Innovation Categories:** Including SAD-Auto-Mapper, ETD-based Deadline Engine, and Profit-per-Booking Dashboards.

### 5W
- **Who:** PJL Connect OPS Team, Customers, Drivers, and Brokers.
- **What:** A comprehensive, chat-native Logistics Operating System.
- **Where:** Regional trade lanes (e.g., Cambodia, Vietnam, Thailand).
- **When:** 24/7 Real-time operational monitoring and automated document collection.
- **Why:** To eliminate "Manual Chaos," increase profit margins, and provide radical transparency.

### Function requirement analysis
- **Communication:** Telegram Bot API for Customer/Driver/Carrier interaction.
- **Multi-Modal Management:** Support for defining and tracking multi-leg shipments (Truck -> Port -> Sea -> Door).
- **Data Capture:** AI/OCR Engine for automated Invoice, Packing List, and B/L or AWB ingestion.
- **Logistics Logic:** Geofencing (Land), Vessel/Flight tracking integration, and multi-vendor escalation.
- **Financial Engine:** Automated split-invoice generation and multi-currency ledger sync.
- **Management Intelligence:** Multi-dimensional volume dashboards (by Mode, Route, Customer, and Period).

### Use cases outline
- **UC-01:** Customer places booking and uploads docs via Bot.
- **UC-02:** OPS assigns carrier and system escalates pings.
- **UC-03:** Driver shares real-time location for geofenced arrival detection.
- **UC-04:** Broker reviews AI-generated Draft SAD for ASYCUDA submission.
- **UC-05:** Accounting syncs finalized OPS financials to corporate ledger.

### Out of scope
- **Physical Warehousing Ops:** (Inventory management, picking/packing).
- **Truck Maintenance:** (Mechanical logs, spare parts procurement).
- **Expense & Reimbursement Tracking:** (Fuel, toll, and driver receipt management).
- **Public B2C Courier:** (System is optimized for B2B/Enterprise logistics).

### User wireframe or user journey outline

#### Journey 1: The "Invisible" Intake (Customer & Bot)
1.  **Trigger:** Customer receives shipment documents (Inv/PL) from their supplier.
2.  **Action:** Customer forwards documents to **PJL Bot** via Telegram.
3.  **Automation:** System OCR extracts Multi-Modal legs (e.g., Truck + Sea), ETD, and commodity details.
4.  **Verification:** Bot sends an **Interactive Summary Card**; Customer taps `Confirm`.
5.  **Aha! Moment:** Customer receives a "Booking Confirmed" ID within 60 seconds without a single phone call.

#### Journey 2: The "Command Center" (OPS & Carrier)
1.  **Trigger:** OPS receives a "New Booking" alert on the **Back-Office Kanban**.
2.  **Action:** OPS reviews the AI-extracted data and clicks `Assign to Carrier`.
3.  **Automation:** System sends a Telegram alert to the Carrier. If no response in 5 mins, the **"Nag-Bot"** escalates.
4.  **Verification:** Carrier clicks `Accept` and shares a QR link with the Driver.
5.  **Moment of Truth:** Driver scans QR, binding their Telegram ID to the shipment instantly.

#### Journey 3: The "Compliance Guard" (Broker & ASYCUDA)
1.  **Trigger:** System detects all required docs (Inv/PL/CO) are uploaded.
2.  **Automation:** **SAD-Mapper** generates a Draft SAD in government-ready XML/JSON.
3.  **Action:** Broker reviews the draft in the Back-office for HS Code accuracy and clicks `Submit to ASYCUDA`.
4.  **Visibility:** System monitors the result and auto-notifies the Customer: "Customs Status: Green Lane/Cleared."

#### Journey 4: The "Dashboard Strategic Sniper" (Management)
1.  **Trigger:** Monday morning review.
2.  **Action:** Manager opens the **Pulse Dashboard**.
3.  **Insight:** Observes that "Vietnam Lane" velocity has slowed down due to brokerage delays.
4.  **Decision:** Manager identifies the bottleneck carrier/broker and makes a data-driven adjustment for next month's planning.

## Risk

- **Driver Indifference:** Drivers forgetting to share location (Mitigation: Carrier "Nag" pings).
- **Signal Blackouts:** Remote areas without GPS/Data (Mitigation: Manual OPS override + Silence Escalation).
- **OCR Inaccuracy:** Dirty scans from customers (Mitigation: "Visual Receipt" correction suites).
- **Compliance Changes:** Government ASYCUDA updates (Mitigation: Modular SAD mapping logic).

## Pre-solution

The system relies on the **"Invisible App Bridge"**—a pre-condition where Telegram IDs are bound to corporate profiles, allowing instant authentication without passwords, enabling immediate operational velocity.

## System Overview

PJL Connect is an **Automation-First Logistics System** designed with a **Hybrid Distribution Architecture**. It is divided into five distinct modules that work in synchrony to manage the entire logistics lifecycle—from the "Invisible" client intake in Telegram to complex multi-dimensional analytics in the Back-Office and real-time internal team coordination.

## Module Design & Feature Matrix

### 1. PJL Connect Back-Office (Web Application - Detailed Site Map)
*The centralized Corporate ERP terminal for internal staff and management.*

| Main Menu | Sub-Menu | Functional Detail | Key Objective | 50 Categories |
| :--- | :--- | :--- | :--- | :--- |
| **#1 Dashboard** | **A: Pulse Snapshot** | Real-time "Operations Health" showing Today's Volume, Active Drivers, and Exception Alerts. | Immediate awareness. | [#33], [#49], [#20] |
| | **B: Strategic Trends** | Multi-dimensional volume charts (By Customer, Period, and Trade-Lane). | Growth identification. | [#41], [#47], [#48], [#11], [#50] |
| | **C: Performance Cards** | Automatic "Carrier Reliability Ranking" and "OPS Workload" analysis. | Resource optimization. | [#42], [#8], [#15] |
| **#2 Booking** | **A: Smart Booking List** | Master view of all shipments (Land/Sea/Air) with status filters and container search. | Centralized tracking. | [#1], [#9], [#30] |
| | **B: Multi-Leg Builder** | Interface to add/edit legs of a journey (e.g., Warehouse Truck -> Port -> Sea Vessel). | Complex logistical sync. | [#7], [#22] |
| | **C: CRM / Customer Profiles** | Managing client contacts, preferred routes, and special service agreements. | Relationship history. | [#21], [#28] |
| **#3 Job** | **A: Job Kanban** | Trello-style board for dragging jobs between Carry-Assignment, In-Transit, and Delivered. | Workflow velocity. | [#15], [#31], [#32] |
| | **B: Job Detail / Compliance** | Comprehensive drill-down for assigned Brokers/OPS to review Inv/PL, verify OCR, and trigger "Copy-Paste Magic." | Unified job control. | [#30], [#2], [#23], [#24], [#37], [#38], [#39], [#45] |
| | **C: Job Nag-Monitor** | Real-time status of carrier acceptance pings and auto-escalation logs. | Guaranteed fulfillment. | [#27] |
| | **D: Exception Terminal** | View for "Silence Escalations" (Lost Pings) and "Manual Overrides" (OPS-pinned locations). | Chaos resiliency. | [#33], [#34], [#35], [#12] |
| **#4 Financial** | **A: Invoice Console** | OPS-pre-populated customer invoices ready for auditor sign-off. | Rapid billing. | [#13], [#40] |
| | **B: Ledger Sync Logs** | One-click push to external accounting software with transaction history. | Seamless reconciliation. | [#16], [#40] |
| **#6 Configurator** | **A: Service Rules** | Matrix to define document requirements (Inv/PL/CO) and nag-intervals per route. | System flexibility. | [#46], [#14] |
| | **B: Identity Center** | Telegram ID binding, passwordless auth settings, and Role-Based Access Control (RBAC). | Secure authentication. | [#1], [#21], [#5], [#28], [#18] |
| | **C: Carrier Database** | Repository of carrier contracts, negotiated rates, and fleet capabilities. | Vendor management. | [#4], [#28] |
| | **D: Customer Database** | Master repository of client profiles, specific pricing rates, and contact management. | Client lifecycle. | [#21] |
| | **E: Template Mapper** | Interface to manage dynamic PDF/Doc templates and map system fields (Job data) to template placeholders. | Document automation. | [#7], [#38] |

### 2. PJL Connect Customer Bot (Telegram - Command Hierarchy)
*The "Invisible App" for high-velocity client interaction.*

| Command | Feature | Function Detail | Key Objective | 50 Categories |
| :--- | :--- | :--- | :--- | :--- |
| **`/start`** | **Profile Onboarding** | Welcome msg with instant Telegram ID binding to Company profile. | Secure passwordless entry. | [#1], [#21] |
| **`/new`** | **Booking Engine** | The 10-step flow: document upload, AI extraction, and "Visual Receipt" confirm. | High-speed intake. | [#1], [#2], [#3], [#22], [#23], [#24] |
| **`/track`** | **Shipment Pulse** | Displays an **Interactive Selection Menu** (Inline Keyboard) of active jobs for the week. User taps a specific Job ID to see live maps and "Customs Lane" status. | Real-time visibility. | [#9], [#3], [#12] |
| **`/docs`** | **Deadline Master** | List of bookings requiring docs; allows decoupled upload before ETD-deadlines. | Compliance safety. | [#45], [#39] |
| **`/help`** | **OPS Support** | Instant hand-off to a human OPS member for complex queries. | Human-in-the-loop safety. | [#5], [#14] |

### 3. PJL Connect Carrier & Driver Bot (Telegram - Execution Terminal)
*The execution portal for vendors and road-level personnel.*

| Command | Feature | Function Detail | Key Objective | 50 Categories |
| :--- | :--- | :--- | :--- | :--- |
| **`/enroll`** | **Carrier Setup** | Digital contract acceptance and QR-based driver profile binding. | Frictionless onboarding. | [#4], [#25], [#28] |
| **`/accept`** | **Booking Dispatch** | The "Nag-Loop" terminal. Carrier accepts bookings via one-tap buttons. | Guaranteed fulfillment. | [#4], [#27] |
| **`/status`** | **Manual Override** | Manual "Arrived/Departed" toggle if geofencing or GPS fails in remote zones. | Resilient tracking. | [#34] |
| **`/location`** | **Tracer Toggle** | Activation of background location sharing. **Duration:** Active until geofencing detects "Delivered" or Job is finalized. **Note:** System re-prompts if 8h Telegram limit is reached. | Hands-free logistcs. | [#10], [#26], [#33], [#17] |

### 4. PJL Connect OPS Internal Bot (Telegram Group)
*The coordination terminal for the internal operations team.*

| Feature | Function Detail | Key Objective | 50 Categories |
| :--- | :--- | :--- | :--- |
| **Real-time Job Alerts** | Instant pings in the OPS Group Chat for "New Booking," "Customs Prep Ready," and "Job Finalized." | Team synchronization. | [#5] |
| **Exception Pings** | Aggressive alerts for "Silence Escalations" or "Carrier Non-Response" to trigger immediate group discussion. | Rapid problem solving. | [#33], [#12] |
| **Collaboration Bridge** | Bot provides deep-links to specific Job Detail pages in the Back-Office for one-tap navigation from chat. | Contextual coordination. | [#29], [#5] |
| **Admin Controls** | Simple commands to list "Stalled Jobs" or check current "Team Workload" directly from Telegram. | Mobile-first oversight. | [#12], [#15] |

### 5. PJL Connect Intelligence Hub (Core Engine)
*The "Brain" connecting the bots, back-office, and external government/financial systems.*

| Core Capability | Function Detail | Key Objective | 50 Categories |
| :--- | :--- | :--- | :--- |
| **Multi-Modal Rule Engine** | Logic to manage transport dependencies between legs (e.g., Sea Freight cannot start until Trucking Leg A is "Delivered"). | Complex multi-leg sync. | [#7] |
| **Smart SAD-Mapper** | Logic that prepares AI-extracted data into specific "Copy-Pasting Blocks" specifically for ASYCUDA submission. | 0% re-typing error. | [#38], [#19] |
| **Document Generator** | Central engine that injects Job/OCR data into the pre-defined templates (Inv/Drafts) for final output. | Automated PDF creation. | [#7] |
| **Tracking Aggregator** | Central engine that merges driver GPS streams with Shipping Line/Flight APIs. Includes **Geofencing Logic** that auto-triggers "Arrived" and "Departed" status updates on the Job Kanban when a driver crosses a coordinate radius (Warehouse/Port). | Autonomous status hygiene. | [#10], [#26], [#42], [#17] |
| **"Silence Escalation" Logic** | Monitoring system "Heartbeats"; if a driver's phone stops pinging, it auto-alerts OPS for a manual pin check. | Operational resiliency. | [#33], [#12] |

## Technical Constraints & Platform Strategy
*(Consolidated into Module Logic)*
- **Primary Interface:** Telegram Bot API (for Bots).
- **Core Technology:** React/Next.js (Back-office), Node.js (Engine/API).
- **Automation Layer:** Cloud AI OCR + Custom Mapping Schemas (ASYCUDA/Ledger).
- **Integration Strategy:**
    - **Customs:** **"Copy-Paste Magic"** bridge. System prepares formatted data for manual broker ingestion (No direct system-to-system integration required). [#38]
    - **Finance:** API-only ledger sync to eliminate manual accounting entry. [#16], [#40]
    - **Track & Trace:** Integration of GPS location streams (Telegram) with external Shipping Line/Airline AIS/Flight tracking data APIs. [#10], [#6], [#17]
