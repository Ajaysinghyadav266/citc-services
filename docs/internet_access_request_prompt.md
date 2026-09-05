# System Enhancement Prompt: Adding Internet Access Request Service

## Context & Project Scope
You are analyzing an existing system that provides institutional IT services for **IIT Indore**. The current system manages three core request workflows, each handled via its own specific form:
1. **VPN Request Form**
2. **VM (Virtual Machine) Request Form**
3. **Web Hosting Request Form**

We need to extend this system by introducing a **fourth service**: the **Internet Access Request Form**. This form must inherit the same structural layout, automated data-population mechanics, API integrations, and validation workflows as the existing services (particularly modeling its backend approval routing after the VPN project).

---

## Functional Requirements for the New Form

Please generate or update the system architecture, UI components, database schemas, and API handlers to incorporate the following multi-step form specification:

### Section 1: Personal & Academic Information
*   **Full Name** `[Required]`
*   **Roll No / Employee ID** `[Required]`
*   **Institutional Email** `[Required]`
    *   *Behavior:* Automatically populated/read-only from the user's logged-in Google account session (e.g., `stest@iiti.ac.in`).
*   **Phone Number** `[Required]`

### Section 2: Institutional Classification & Approval Routing
*   **Approver Email ID** `[Required, User Input]`
*   **Automated Profiles:** 
    *   *Behavior:* Just like the existing **VPN request workflow**, the system must consume the provided `Approver Email ID` or the applicant's identity to query the internal **IIT Indore ERP API**.
    *   *Data to Fetch:* Automatically populate the remaining fields such as **Name**, **Designation**, and **Department** from the ERP database. These fields should be read-only to ensure authenticity.

### Section 3: Hardware & Technical Profile
*   **Device Type** `[Required]` (e.g., Laptop, Desktop, Mobile)
*   **Operating System** `[Required]` (e.g., Windows, macOS, Linux, Android, iOS)
*   **MAC Address** `[Required]`
    *   *Validation Regular Expression:* Must enforce standard formats: `^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$` (Accepts `XX:XX:XX:XX:XX:XX` or `XX-XX-XX-XX-XX-XX`).
*   **Connection Duration** `[Required]` (Radio / Single Selection)
    *   📅 `Semester`
    *   📆 `Annual`
    *   ♾️ `Permanent`

### Footer & Submission
*   **Policy Agreement text:** *"By submitting, you agree to abide by IIT Indore's IT Usage Policy."*
*   **Action:** Submit Request button that triggers the backend verification, ERP lookup validation, and approval notification loop.

---

## Tasks for the AI Developer / Architect
1.  **UI/UX Integration:** Describe where this new service card/tab sits alongside VPN, VM, and Web Hosting. Provide the front-end code snippet or structural component updates matching the design system.
2.  **API & Backend Extensions:** Design the endpoint handler (e.g., `POST /api/requests/internet-access`) that processes this schema, executes the ERP API hook for verification, and saves the record.
3.  **Database Schema:** Provide the database migration script or schema definition (SQL/NoSQL) required to store this fourth type of request.
4.  **Workflow Logic:** Outline the notification or state machine transition (Pending -> Approved/Rejected by ERP-fetched Approver) modeled after the existing VPN validation logic.

---

## ✅ Implementation Log

> **Implemented on:** 2026-07-29

### Files Created

| File | Purpose |
|------|---------|
| `database/migrations/2026_07_29_100000_create_internet_access_requests_table.php` | DB migration — 14-column `internet_access_requests` table with `status` enum |
| `app/Models/InternetAccessRequest.php` | Eloquent model with `$fillable` for all request fields |
| `app/Http/Controllers/InternetAccessController.php` | Controller: `create()`, `store()` (validation + DB save + mail), `success()` |
| `resources/views/internet-access/create.blade.php` | 3-step form (Personal → Approver → Device) with ERP auto-fetch & MAC validation |
| `resources/views/internet-access/success.blade.php` | Animated success/confirmation page with next-steps card |

### Files Modified

| File | Change |
|------|--------|
| `routes/web.php` | Added `GET /internet-access`, `POST /internet-access`, `GET /internet-access/success` routes |
| `resources/views/dashboard.blade.php` | Added **Internet Access Request** service card (teal) below the VPN button |

### Key Design Decisions
- **ERP Reuse:** The existing `GET /get-approver` endpoint is reused with no changes — zero duplication.
- **MAC Validation:** Enforced both client-side (JS) and server-side (Laravel `regex` rule).
- **3-Step Wizard:** Form split into 3 sections with step indicator dots; JS validates each step before advancing.
- **Dark Glassmorphism UI:** Matches the existing dashboard aesthetic (Tailwind, blobs, `backdrop-blur`).
- **Status Field:** `pending` default with enum `(pending, approved, rejected)` for future approval workflow.

---

## 🔄 Change Log — v2 (2026-07-29)

### Updated
| File | Change |
|------|--------|
| `resources/views/internet-access/create.blade.php` | **Redesigned** — replaced multi-step wizard with a single-page flat form matching the VPN form style: plain white background (`bg-gray-100`), all sections visible at once, same Tailwind card layout as `vpn-form.blade.php` |

### Reason
User requested the form to match the existing VPN form exactly — plain white screen, single page, no wizard steps.

---

## 🔄 Change Log — v3 (2026-07-29)

### New Landing Page at `/`

| File | Action |
|------|--------|
| `resources/views/home.blade.php` | **[NEW]** Landing page inspired by hms.iiti.ac.in — light blue gradient, hero text left + service cards right |
| `routes/web.php` | **[MODIFIED]** Added `GET /` route pointing to `home.blade.php` |
| `app/Http/Controllers/AuthController.php` | **[MODIFIED]** `redirectToGoogle()` now reads `?redirect=` query param and stores it in session; `handleGoogleCallback()` uses `session()->pull('citc_redirect')` to route user directly to their chosen form after login |
| `resources/views/welcome.blade.php` | **[MODIFIED]** Login page now forwards `?redirect=` param through to `/login/google` URL; added "← Back to Home" link |

### Behaviour
- Clicking a service card (e.g. **VPN Access**) on the home page links to `/login?redirect=vpn-form`
- Login page passes this to `/login/google?redirect=vpn-form`
- `AuthController` stores `vpn-form` in session before OAuth redirect
- After successful Google login, user is sent directly to `/vpn-form` (skipping dashboard)
- Normal "Login" button has no `?redirect=` and goes to `/dashboard` as before

---

## 🐛 Bugfix — v3.1 (2026-07-29)

**Issue:** Clicking a service card still redirected to `/dashboard` after login.

**Root cause:** `session()->forget('url.intended')` was actively destroying the URL stored by the `auth` middleware. The `?redirect=` session approach was also fragile across OAuth redirects.

**Fix — used Laravel's built-in intended URL mechanism:**

| File | Change |
|------|--------|
| `app/Http/Controllers/AuthController.php` | Removed `session()->forget('url.intended')` and all `citcRedirect` logic. Now uses `redirect()->intended('/dashboard')` which reads the URL stored by the `auth` middleware. |
| `resources/views/home.blade.php` | Card links now point **directly** to form routes (e.g. `/vpn-form`) instead of `/login?redirect=...`. Auth middleware intercepts, stores the URL, redirects to login. |
| `resources/views/welcome.blade.php` | Reverted Google login link to plain `/login/google` — no more `?redirect=` forwarding needed. |

**How it works now:**
1. User clicks **VPN Access** card → goes to `/vpn-form`
2. `auth` middleware sees unauthenticated user → stores `/vpn-form` as `url.intended` → redirects to `/login`
3. User logs in via Google OAuth
4. `redirect()->intended('/dashboard')` reads `url.intended` → sends user to `/vpn-form` ✅

---

## 🎨 Change Log — v4 (2026-07-30) — Dashboard & Navbar Redesign

### New Files

| File | Purpose |
|------|---------|
| `resources/views/layouts/dashboard.blade.php` | **[NEW]** Shared navbar layout — IIT Indore emblem, Hindi+English name, pill nav (Home / VPN / Internet Access / VM / Web Hosting / My Requests), user avatar + logout |
| `resources/views/my-requests.blade.php` | **[NEW]** Placeholder "My Requests" page using shared layout |

### Modified Files

| File | Change |
|------|--------|
| `resources/views/dashboard.blade.php` | Redesigned — extends `layouts.dashboard`, shows "Hello [first name]" greeting + 4 service cards with colored bottom-border accents |
| `resources/views/vpn-form.blade.php` | Now extends `layouts.dashboard` (gets navbar automatically) |
| `resources/views/internet-access/create.blade.php` | Now extends `layouts.dashboard` |
| `resources/views/hosting/create.blade.php` | Now extends `layouts.dashboard` |
| `resources/views/vm-requests.blade.php` | Replaced old VM-specific navbar with shared IIT Indore navbar (inline CSS, keeps Vite build) |
| `resources/views/vpn-success.blade.php` | Now extends `layouts.dashboard` |
| `routes/web.php` | Added `GET /my-requests` stub route |

### Design
- **Inspired by:** beta.iiti.ac.in — IIT emblem + Hindi/English identity, divider line, pill-shaped nav
- **Active nav highlighting:** Current page nav item gets `bg-blue-900 text-white` pill
- **Dashboard home:** First name greeting, 4 service cards (indigo/cyan/green/orange), info strip

---

## 🔧 Change Log — v5 (2026-07-30) — Web Host & VM Approver + Google Auth

### Modified Files

| File | Change |
|------|--------|
| `resources/views/hosting/create.blade.php` | `institute_email` and `owner_name` now pre-filled (readonly) from `auth()->user()`. Added **Approver Details** section (email input → ERP auto-populate for name/designation/department). JS validates approver is filled before submit. |
| `resources/views/vm-requests.blade.php` | Added **Approver Details** section between "Requester Details" and "VM Details" bands. Same ERP API auto-populate pattern (`/get-approver?email=`). JS validates approver on `#vmRequestForm` submit. |

### Behaviour (both forms)
1. User types approver's `@iiti.ac.in` email and tabs out
2. JS calls `GET /get-approver?email=...`
3. ERP API returns `{ name, designation, department }`
4. Readonly fields auto-fill instantly
5. If approver fields are empty on submit, form is blocked with an alert

---

## 🔐 Change Log — v6 (2026-07-30) — 3-Tier Approver Workflow

### Approval State Machine
```
Student submits → pending_approver1
  → Approver 1 approves → pending_approver2
    → Dean DITA approves → pending_citc
      → CITC marks complete → completed
  (any stage) → rejected
```

### Role Detection (at /approver-login)
| Condition | Role |
|-----------|------|
| email = `doita@iiti.ac.in` | Approver 2 (Dean DITA) |
| ERP dept contains `Computer and Information Technology Center` | Approver 3 (CITC) |
| Any other ERP user | Approver 1 (Faculty/Staff) |

### New Files
| File | Purpose |
|------|---------|
| `database/migrations/2026_07_30_000001_add_approval_workflow_to_request_tables.php` | Adds `approval_status` enum + timestamp cols to all 4 tables; adds `approver_*` cols to vm & hosting tables |
| `app/Http/Controllers/ApproverController.php` | dashboard / pending / approved / rejected / completed / approve / reject |
| `resources/views/layouts/approver.blade.php` | Shared approver navbar (role-aware pill nav, role badge) |
| `resources/views/approver/dashboard.blade.php` | Count cards (Pending/Approved/Rejected for role 1&2; Pending/Completed for CITC) |
| `resources/views/approver/pending.blade.php` | Approve+Forward & Reject (with reason) actions per request |
| `resources/views/approver/approved.blade.php` | Table of requests already forwarded |
| `resources/views/approver/rejected.blade.php` | Table of rejected requests with reason |
| `resources/views/approver/completed.blade.php` | CITC-only completed requests table |

### Modified Files
| File | Change |
|------|--------|
| `app/Http/Controllers/AuthController.php` | Added `approverRedirectToGoogle()`. Callback detects role and stores in session. |
| `app/Models/VpnRequest.php` | Added workflow cols to `$fillable` + `$casts` |
| `app/Models/VmRequest.php` | Added approver + workflow cols to `$fillable` |
| `app/Models/WebHostingRequest.php` | Added approver + workflow cols to `$fillable` |
| `app/Models/InternetAccessRequest.php` | Added workflow cols to `$fillable` |
| `routes/web.php` | Added `ApproverController` import, `/approver-login` route, full `/approver/*` prefix group (7 routes) |
| `resources/views/layouts/dashboard.blade.php` | Removed "Home" pill link (logo handles it) |
| `resources/views/vm-requests.blade.php` | Removed "Home" from inline navbar |
