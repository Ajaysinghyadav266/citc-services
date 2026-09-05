# ✅ CITC Services — Manual Testing Checklist

> **Branch:** `testing` (pulled from `main`)  
> **Base URL:** `http://127.0.0.1:8000`  
> **Run server:** `php artisan serve`  
> **Last updated:** 2026-09-06  

---

## Legend

| Symbol | Meaning |
|--------|---------|
| `[ ]` | Not tested yet |
| `[P]` | Pass ✅ |
| `[F]` | Fail ❌ |
| `[S]` | Skip (not applicable) |

---

## 0. Pre-Test Setup

> Run these before starting any test. Every step must pass before moving on.

- [ ] `composer install` completes without errors
- [ ] `npm install && npm run build` completes (Vite assets compiled)
- [ ] `.env` has correct `DB_CONNECTION`, `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` set
- [ ] `php artisan migrate:fresh` runs without errors
- [ ] `php artisan serve` starts on `http://127.0.0.1:8000`
- [ ] Database file (`database/database.sqlite`) exists (for SQLite) OR MySQL connection confirmed
- [ ] `APP_KEY` is set in `.env` (`php artisan key:generate` if missing)
- [ ] Mail config set (SMTP or `log` driver for local testing)

---

## 1. Public Pages (No Login Required)

### 1.1 Home / Landing Page — `GET /`

- [ ] Page loads without a 500 error
- [ ] IIT Indore logo/emblem renders correctly
- [ ] Page title is "CITC Services" or similar (not "Laravel")
- [ ] All 4 service cards are visible: **VPN**, **Internet Access**, **VM Request**, **Web Hosting**
- [ ] Each service card click redirects to the correct form URL (may prompt login)
- [ ] "Approver Login" link is visible and points to `/approver-login`
- [ ] Page is responsive on mobile viewport (375px width)

### 1.2 Login Page — `GET /login`

- [ ] Page loads without errors
- [ ] "Sign in with Google" button is visible
- [ ] Clicking the Google login button redirects to Google OAuth (URL starts with `accounts.google.com`)
- [ ] Page shows IIT Indore branding

### 1.3 Approver Login Page — `GET /approver-login`

- [ ] Page loads without errors
- [ ] "Sign in with Google (Approver)" button is visible
- [ ] Clicking redirects to Google OAuth

---

## 2. Authentication — Student (Google OAuth)

### 2.1 Successful Login

- [ ] Logging in with a valid `@iiti.ac.in` Google account redirects to `/dashboard`
- [ ] After login, user's name appears in the navbar
- [ ] After login, navigating to `/login` redirects to `/dashboard` (already authenticated)

### 2.2 Direct Form URL Before Login

- [ ] Visiting `/vpn-form` without login redirects to `/login`
- [ ] Visiting `/vm-request-application/new` without login redirects to `/login`
- [ ] Visiting `/web-host` without login redirects to `/login`
- [ ] Visiting `/my-requests` without login redirects to `/login`
- [ ] After login from a service URL, user is sent directly to that form (intended URL behavior)

### 2.3 Logout

- [ ] Clicking "Logout" button sends `POST /logout`
- [ ] After logout, user is redirected to `/login`
- [ ] After logout, navigating to `/dashboard` redirects to `/login`
- [ ] After logout, session is fully cleared (no stale data)

---

## 3. Student Dashboard — `GET /dashboard`

- [ ] Dashboard loads after login
- [ ] "Hello [First Name]" greeting shows correct name
- [ ] All 4 service cards are visible with correct labels
- [ ] Navbar pill links are present: **Home**, **VPN**, **Internet Access**, **VM**, **Web Hosting**, **My Requests**
- [ ] Active nav item is highlighted
- [ ] Clicking each nav pill goes to the correct route
- [ ] Logo click returns to home (`/`)

---

## 4. VPN Access Form — `GET /vpn-form`

### 4.1 Form Rendering

- [ ] Form loads without errors
- [ ] Student name and email are **pre-filled** (readonly) from logged-in user
- [ ] All required fields present: Contact, OS, Start Date, End Date, Purpose, Resources, Approver Email

### 4.2 Approver Auto-Fetch (ERP API)

- [ ] Typing a valid `@iiti.ac.in` email in the Approver Email field and tabbing out triggers the ERP API call (`GET /get-approver?email=...`)
- [ ] Approver Name, Designation, and Department auto-fill correctly
- [ ] Typing an invalid/non-existent email shows an appropriate empty or error state
- [ ] Auto-filled fields are **read-only** (cannot be manually edited)

### 4.3 Form Validation

- [ ] Submitting with no Contact → error shown
- [ ] Submitting with an invalid phone number → error shown
- [ ] Submitting with Start Date after End Date → error shown (or validated on backend)
- [ ] Submitting without approver details filled → form is blocked with an error message
- [ ] Submitting a complete, valid form → redirects to `/vpn-success`

### 4.4 Submission & DB

- [ ] After successful submission, a new row appears in the `vpn_requests` table
- [ ] `approval_status` is `pending` in DB
- [ ] `approver_email` is correctly saved
- [ ] Confirmation email is sent to student (check mail log / inbox)
- [ ] Approver CC email is sent (check mail log)

### 4.5 Success Page

- [ ] `/vpn-success` loads and shows a success message
- [ ] "Back to Dashboard" or similar navigation link is present

---

## 5. Internet Access Request — `GET /internet-access`

### 5.1 Form Rendering

- [ ] Form loads without errors (no conflict markers `<<<<<<<` visible on page)
- [ ] Student name/email pre-filled from auth (if applicable)
- [ ] All required fields present

### 5.2 Approver Auto-Fetch

- [ ] Approver email field triggers ERP API lookup on blur
- [ ] Name, designation, department auto-fill correctly

### 5.3 Form Validation

- [ ] Submitting empty form → validation errors shown
- [ ] Submitting with approver fields empty → submission blocked

### 5.4 Submission

- [ ] Successful submission redirects to `/internet-access/success`
- [ ] A new row appears in `internet_access_requests` table with `approval_status = pending`
- [ ] Confirmation email sent

### 5.5 Success Page — `GET /internet-access/success`

- [ ] Page loads correctly
- [ ] Shows confirmation message

---

## 6. VM Request Form — `GET /vm-request-application/new`

### 6.1 Form Rendering

- [ ] Form loads without errors
- [ ] No conflict markers visible in source
- [ ] Title shows "New VM Request Application" and subtitle "Indian Institute of Technology Indore — CITC"
- [ ] All sections visible: Requester Details, Approver Details, VM Details

### 6.2 Approver Auto-Fetch

- [ ] Approver email input triggers ERP fetch on blur
- [ ] Name/designation/department fill correctly
- [ ] Invalid email leaves fields empty

### 6.3 Form Validation

- [ ] `purpose_usage` less than 5 characters → inline error "Describe the purpose in at least 5 characters."
- [ ] `justification` less than 5 characters → inline error "Provide a justification of at least 5 characters."
- [ ] `cpu_cores` outside 1–64 → error
- [ ] `ram_gb` outside 1–512 → error
- [ ] `hard_disk_gb` outside 1–10000 → error
- [ ] Submitting valid form → redirects to `/dashboard` after ~1 second toast

### 6.4 Submission & DB

- [ ] New row in `vm_requests` with `approval_status = pending`
- [ ] `approver_email`, `approver_name`, `approver_designation`, `approver_department` all saved
- [ ] Confirmation email sent

---

## 7. Web Hosting Request — `GET /web-host`

### 7.1 Form Rendering

- [ ] Form loads without errors
- [ ] No conflict markers in rendered HTML
- [ ] Institute email and owner name pre-filled from `auth()->user()`
- [ ] Approver Details section is present (Section 2)

### 7.2 Approver Auto-Fetch

- [ ] Approver email triggers ERP lookup
- [ ] Name/designation/department auto-fill

### 7.3 Form Validation

- [ ] Required fields validated before submission
- [ ] Approver details required

### 7.4 Submission

- [ ] `POST /submit` → success redirect
- [ ] New row in `web_hosting_requests` table with `approval_status = pending`

---

## 8. My Requests — `GET /my-requests`

- [ ] Page loads without errors
- [ ] Shows all requests submitted by the logged-in user (VPN, Internet, VM, Hosting)
- [ ] Each request shows its current `approval_status` correctly
- [ ] Status badges display: `pending`, `approved_by_1`, `approved_by_2`, `completed`, `rejected`
- [ ] Empty state shown if no requests submitted yet
- [ ] Date of submission is displayed correctly
- [ ] Rejection reason is shown for rejected requests

---

## 9. Approver Flow — Level 1 (Faculty/Staff)

### 9.1 Login

- [ ] Visit `/approver-login` → click Google sign-in
- [ ] Log in with a valid `@iiti.ac.in` faculty/staff account (not `ftest@iiti.ac.in`, not CITC dept)
- [ ] After OAuth callback, redirected to `/approver/dashboard`
- [ ] Session contains `approver_level = 1`

### 9.2 Dashboard

- [ ] `/approver/dashboard` loads without errors
- [ ] "Level 1 Approver" badge or label visible
- [ ] Pending count card shows correct number (requests where `approver_email` matches)
- [ ] Approved count and Rejected count displayed
- [ ] Recent requests list shows up to 5 items

### 9.3 Pending Requests — `GET /approver/pending`

- [ ] Only requests where `approver_email = logged-in approver email` and `approval_status = pending` are shown
- [ ] Request details are visible: type, requester name, date
- [ ] "Approve" and "Reject" buttons present for each request

### 9.4 Approve Action

- [ ] Clicking "Approve" on a VPN request → `POST /approver/approve/vpn/{id}`
- [ ] Request `approval_status` changes to `approved_by_1` in DB
- [ ] `approver1_email`, `approver1_name`, `approved_by_1_at` are saved
- [ ] Success flash message shown
- [ ] Notification email sent to Dean IT (L2) that request awaits review
- [ ] Test same for: Internet Access, VM Request, Web Hosting

### 9.5 Reject Action

- [ ] Clicking "Reject" opens a reason input field/modal
- [ ] Submitting without a reason → validation error (reason required, max 1000 chars)
- [ ] Submitting with a reason → `POST /approver/reject/vpn/{id}`
- [ ] `approval_status` changes to `rejected`, `rejected_by_level = 1`, `rejection_reason` saved
- [ ] Rejection email sent to requester
- [ ] Test same for all 4 request types

### 9.6 Approved Requests — `GET /approver/approved`

- [ ] Shows only requests this approver forwarded (not pending, not rejected)

### 9.7 Rejected Requests — `GET /approver/rejected`

- [ ] Shows only requests rejected by Level 1 (`rejected_by_level = 1`)
- [ ] Rejection reason is visible

### 9.8 Access Control

- [ ] L1 approver visiting `/approver/citc/pending` → 403 error
- [ ] L1 approver visiting `/approver/all-requests` → 403 error

---

## 10. Approver Flow — Level 2 (Dean IT / `ftest@iiti.ac.in`)

### 10.1 Login

- [ ] Log in via `/approver-login` with Google account `ftest@iiti.ac.in`
- [ ] Redirected to `/approver/dashboard`
- [ ] `approver_level = 2` in session

### 10.2 Dashboard

- [ ] Dashboard shows Level 2 badge
- [ ] Pending count = requests with `approval_status = approved_by_1`
- [ ] Approved count = requests with `approved_by_2` or `completed`

### 10.3 Pending Requests

- [ ] Only requests with `approval_status = approved_by_1` are shown (all types)
- [ ] L2 can see requests regardless of which L1 approver forwarded them

### 10.4 Approve Action

- [ ] Approving → `approval_status` changes to `approved_by_2`
- [ ] `approver2_email`, `approver2_name`, `approved_by_2_at` saved
- [ ] No email sent at this stage (CITC will see it in their dashboard)

### 10.5 Reject Action

- [ ] Rejecting with a reason → `rejected_by_level = 2`, reason saved
- [ ] Rejection email sent to requester

### 10.6 Access Control

- [ ] L2 visiting `/approver/citc/pending` → 403

---

## 11. Approver Flow — Level 3 (CITC)

### 11.1 Login

- [ ] Log in via `/approver-login` with a CITC department Google account
- [ ] ERP API returns department containing "Computer and Information Technology Center"
- [ ] `approver_level = 3` in session
- [ ] Redirected to `/approver/dashboard`

### 11.2 Dashboard

- [ ] Shows Level 3 / CITC badge
- [ ] Pending count = requests with `approval_status = approved_by_2`
- [ ] Completed count = requests with `approval_status = completed`

### 11.3 CITC Pending — `GET /approver/citc/pending`

- [ ] Only requests with `approval_status = approved_by_2` shown
- [ ] "Mark Complete" button present for each request

### 11.4 Mark Complete Action

- [ ] Clicking complete → `POST /approver/approve/{type}/{id}`
- [ ] `approval_status` changes to `completed`
- [ ] `citc_completed_by`, `citc_completed_at` saved
- [ ] Completion email sent to requester

### 11.5 CITC Completed — `GET /approver/citc/completed`

- [ ] All completed requests visible

### 11.6 All Requests Admin — `GET /approver/all-requests`

- [ ] Full list of all requests (all 4 types, all statuses) visible to L3
- [ ] Filter by `?status=pending` works correctly
- [ ] Filter by `?type=VPN` works correctly
- [ ] Combined filters work
- [ ] Delete button visible for each request

### 11.7 Delete Action

- [ ] Clicking delete → `DELETE /approver/delete/{type}/{id}`
- [ ] Record permanently removed from DB
- [ ] Success flash shown
- [ ] L1 and L2 approvers cannot access delete endpoint → 403

---

## 12. ERP API Integration — `GET /get-approver`

- [ ] Valid IITI email → returns `name`, `designation`, `department` JSON
- [ ] Non-existent email → returns empty fields (no 500 error)
- [ ] API times out gracefully (no unhandled exception, timeout set to 8s)
- [ ] Response is correct JSON: `{"name":"...","designation":"...","department":"..."}`

---

## 13. Email Notifications

> Use `MAIL_MAILER=log` locally and check `storage/logs/laravel.log`

| Event | Recipient | Expected |
|-------|-----------|----------|
| Form submitted | Student | Confirmation email |
| Form submitted | Approver (CC) | Notification to review |
| L1 approves | Dean IT (L2) | "Request awaiting your review" email |
| L3 completes | Student | "Your request is fulfilled" email |
| Any level rejects | Student | "Your request was rejected" + reason |

- [ ] Submission confirmation email logged/received
- [ ] L1 approval notification to L2 logged/received
- [ ] Rejection email with reason logged/received
- [ ] Completion email to requester logged/received

---

## 14. Security & Access Control

### 14.1 Unauthenticated Access

- [ ] `GET /dashboard` without login → 302 redirect to `/login`
- [ ] `GET /vpn-form` without login → 302 redirect to `/login`
- [ ] `GET /approver/dashboard` without login → 302 redirect to `/login`
- [ ] `POST /vpn-submit` without login → 302 redirect to `/login`

### 14.2 Approver-Only Routes

- [ ] Logged-in student visiting `/approver/dashboard` → 403 (no `approver_level` in session)
- [ ] Logged-in student visiting `/approver/pending` → 403
- [ ] Logged-in L1 approver visiting `/approver/citc/pending` → 403
- [ ] Logged-in L1 approver visiting `/approver/all-requests` → 403
- [ ] Logged-in L2 approver visiting `/approver/citc/pending` → 403

### 14.3 CSRF Protection

- [ ] Submitting any POST form without the CSRF token → 419 error
- [ ] All forms include `@csrf` in the HTML source

### 14.4 Invalid IDs

- [ ] `POST /approver/approve/vpn/999999` (non-existent ID) → 404
- [ ] `POST /approver/reject/vm/999999` → 404
- [ ] `DELETE /approver/delete/vpn/999999` → 404

### 14.5 Invalid Type

- [ ] `POST /approver/approve/invalidtype/1` → error flash "Unknown request type."

---

## 15. UI / UX Checks

- [ ] No raw PHP errors or stack traces visible on any page
- [ ] No `<<<<<<< HEAD` or `>>>>>>> commit` conflict markers visible in any rendered page
- [ ] All form validation errors are shown inline (not just in console)
- [ ] Flash success messages (green) appear and disappear properly
- [ ] Flash error messages (red) appear correctly
- [ ] Toast notifications (VM form) appear and fade correctly
- [ ] All navbar links correct (no dead/404 links)
- [ ] Logo is visible and renders on all pages
- [ ] Forms are usable on mobile (375px) — no overflow, no broken layout
- [ ] Page titles (`<title>`) are descriptive on all pages

---

## 16. Known PHP Warnings (Non-Blocking)

> These warnings appear in `php artisan serve` output but do **not** affect functionality.
> They are caused by a PHP version mismatch with installed PECL extensions. Safe to ignore for development.

- `Unable to load dynamic library 'pdo_odbc'`
- `Unable to load dynamic library 'pdo_pgsql'`
- `Unable to load dynamic library 'pgsql'`
- `Unable to load dynamic library 'tokenizer'`
- `Unable to load dynamic library 'xml'`
- `Unable to load dynamic library 'ctype'`
- `Unable to load dynamic library 'json'`
- `Unable to load dynamic library 'bcmath'`
- `Unable to load dynamic library 'zip'`

**Optional fix:** Comment out the missing extensions in `php.ini` (run `php --ini` to find its location).

---

## 17. Regression Checklist (After Any Code Change / PR Merge)

> Run these quickly every time a feature branch is merged into `main`.

- [ ] Home page loads
- [ ] Student can log in via Google
- [ ] Student can submit a VPN form
- [ ] Approver L1 can log in and approve a request
- [ ] Approver L2 can see and approve L1-forwarded requests
- [ ] Approver L3 can mark as completed
- [ ] My Requests page shows updated statuses
- [ ] No 500 errors on any main route

---

## 18. Full Workflow End-to-End Test

> This simulates the entire lifecycle of one request from submission to completion.

- [ ] **Step 1 — Submit:** Log in as student → submit a VPN form with valid data
- [ ] **Step 2 — Verify DB:** Check `vpn_requests` table → `approval_status = pending`
- [ ] **Step 3 — L1 Approve:** Log in as L1 approver → pending list shows the request → click Approve
- [ ] **Step 4 — Verify DB:** `approval_status = approved_by_1`, `approver1_email` saved
- [ ] **Step 5 — L2 Approve:** Log in as L2 (`ftest@iiti.ac.in`) → pending shows the request → click Approve
- [ ] **Step 6 — Verify DB:** `approval_status = approved_by_2`, `approver2_email` saved
- [ ] **Step 7 — L3 Complete:** Log in as CITC → CITC Pending shows the request → click Complete
- [ ] **Step 8 — Verify DB:** `approval_status = completed`, `citc_completed_at` saved
- [ ] **Step 9 — Student View:** Log in as student → My Requests → status shows "Completed"
- [ ] **Step 10 — Email:** Completion email logged in `storage/logs/laravel.log`

---

## 19. Test Result Summary

| Feature Area | Tester | Date | Pass / Fail | Notes |
|---|---|---|---|---|
| Pre-Test Setup | | | | |
| Public Pages | | | | |
| Auth — Student Login | | | | |
| Student Dashboard | | | | |
| VPN Form | | | | |
| Internet Access Form | | | | |
| VM Request Form | | | | |
| Web Hosting Form | | | | |
| My Requests | | | | |
| Approver L1 Flow | | | | |
| Approver L2 Flow | | | | |
| Approver L3 / CITC | | | | |
| ERP API Integration | | | | |
| Email Notifications | | | | |
| Security / Access Control | | | | |
| UI / UX | | | | |
| End-to-End Workflow | | | | |

---

> **Total test cases:** ~130+  
> **Tester:** ___________________  
> **Date of testing:** ___________________  
> **Commit hash:** Run `git rev-parse --short HEAD` to get current hash
