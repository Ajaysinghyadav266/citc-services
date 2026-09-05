# CITC Services Portal — IIT Indore

> A web-based service request management system for the **Computer and Information Technology Center (CITC)** at IIT Indore.
> Students and staff can apply for IT services (VPN, Internet Access, VM Machines, Web Hosting) through a structured **3-tier approval workflow** with automatic email notifications.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Tech Stack](#2-tech-stack)
3. [System Architecture](#3-system-architecture)
4. [Approval Workflow](#4-approval-workflow)
5. [Email Notification Pipeline](#5-email-notification-pipeline)
6. [Prerequisites](#6-prerequisites)
7. [Local Setup](#7-local-setup)
8. [Environment Configuration](#8-environment-configuration)
9. [Database Setup](#9-database-setup)
10. [Running the Application](#10-running-the-application)
11. [Approver Role System](#11-approver-role-system)
12. [Artisan Commands and Scheduler](#12-artisan-commands-and-scheduler)
13. [Testing the Email System Locally](#13-testing-the-email-system-locally)
14. [Project Structure](#14-project-structure)
15. [Database Schema](#15-database-schema)
16. [Routes Reference](#16-routes-reference)
17. [Deployment Checklist](#17-deployment-checklist)
18. [Contributing](#18-contributing)

---

## 1. Project Overview

The CITC Services Portal replaces manual, paper-based IT service requests at IIT Indore with a digital workflow. Users sign in with their IIT Indore Google accounts, submit requests, and the system automatically routes them through a three-level approval chain.

### Available Services

| Service | Form Route | Description |
|---|---|---|
| **VPN Access** | `/vpn-form` | Request VPN credentials for secure remote access |
| **Internet Access** | `/internet-access` | Apply for internet access registration |
| **VM Machine** | `/vm-request-application/new` | Request a virtual machine from CITC |
| **Web Hosting** | `/web-host` | Request hosting space for a project or website |

### Key Features

- Google OAuth login for students/staff and approvers (separate flows)
- 3-tier approval workflow: L1 (Faculty Approver) -> L2 (Dean IT) -> L3 (CITC Team)
- Automated email notifications at every approval stage
- Reminder emails for requests pending more than 24 hours
- "My Requests" dashboard for users to track their submissions
- "All Requests" admin view for the CITC team (Level 3) with delete capability
- Asynchronous mail dispatch — page loads instantly, emails send in background

---

## 2. Tech Stack

| Layer | Technology |
|---|---|
| **Framework** | Laravel 12 (PHP 8.3+) |
| **Database** | SQLite (local) / MySQL (production) |
| **Authentication** | Laravel Socialite + Google OAuth 2.0 |
| **Email** | Laravel Mail (SMTP) with `afterResponse()` async dispatch |
| **Queue / Jobs** | Laravel Queue (database driver) |
| **Session** | Database-backed sessions |
| **Frontend** | Blade templates + Vanilla CSS + Vite |
| **ERP Integration** | IIT Indore ERP API (`erpone.iiti.ac.in`) |
| **Scheduler** | Laravel Task Scheduler (cron) |

---

## 3. System Architecture

```
User (Student/Staff)
        |
        v
  Google OAuth Login ─────────────────────────────┐
        |                                          |
        v                                          v
  User Dashboard                        Approver OAuth Login
  (/dashboard)                          (/approver-login)
        |                                          |
        v                                          |
  Service Request Form          ┌───────────────────┤
  (VPN / Internet /             |                  |
   VM / Web Hosting)            v                  v
        |                  Level 1            Level 2
        |              (Faculty/Staff)      (Dean IT)
        v                  Approver           Approver
  Request saved               |                  |
  to Database                 |                  v
        |                     |             Level 3 (CITC)
        |                     |             Admin View
        v                     |             /approver/all-requests
  Email: User                 |
  Email: L1 Approver          |
                              v
                     Email: L2 (Dean IT)
                              |
                              v
                     Email: User (Completed or Rejected)
```

---

## 4. Approval Workflow

Every request goes through the same pipeline regardless of service type.

### Status Flow

```
pending  ->  approved_by_1  ->  approved_by_2  ->  completed
                                                       ^
                                                 (CITC marks done)

Any stage can transition to -> rejected
```

### Level Breakdown

| Level | Who | Action | Trigger |
|---|---|---|---|
| **L1** | Faculty/Staff Approver (auto-detected from ERP) | Approve or Reject | User submits request |
| **L2** | Dean IT — hardcoded email (`CITC_HEAD_EMAIL`) | Approve or Reject | L1 approves |
| **L3 / CITC** | CITC Department staff | Mark Complete or Delete | L2 approves |

### How the L1 Approver is Determined

When a user fills a service form, the system calls the IIT Indore ERP API:

```
GET https://erpone.iiti.ac.in/api/method/telephone_directory.api.get_user_details
    ?email=<user_institute_email>
```

The API returns the user's supervisor/department details. The approver email and name are extracted and stored on the request record in the `approver_email` and `approver_name` columns.

### Approval Status Values

| Value | Meaning |
|---|---|
| `pending` | Waiting for Level 1 approval |
| `approved_by_1` | Level 1 approved, waiting for Dean IT |
| `approved_by_2` | Dean IT approved, waiting for CITC to fulfill |
| `completed` | CITC has fulfilled the request |
| `rejected` | Rejected at any level |

---

## 5. Email Notification Pipeline

All emails are sent **asynchronously** using `dispatch(...)->afterResponse()`. The HTTP response is returned to the browser first and the SMTP handshake happens in the background. This ensures slow mail servers do not delay page loads.

### Email Events

| Event | Recipients | Subject |
|---|---|---|
| Request submitted | User | `[IIT Indore] {Service} Request Submitted` |
| Request submitted | L1 Approver | `[IIT Indore] Action Required: New {Service} Request` |
| L1 approves | Dean IT (L2) | `[IIT Indore] Action Required: {Service} Request — L1 Approved` |
| L1 also notified | L1 Approver | Separate copy confirming request was forwarded to L2 |
| CITC completes | User | `[IIT Indore] Request Fulfilled` |
| Any rejection | User | `[IIT Indore] Request Rejected` |
| Pending more than 24h at L1 | L1 Approver | `[IIT Indore] Reminder: {Service} Request Awaiting Your Approval` |
| Pending more than 24h at L2 | Dean IT (L2) | `[IIT Indore] Reminder: {Service} Request Awaiting Your Approval (L2)` |

### Email Template

All emails use a branded HTML template with:
- IIT Indore CITC Services gradient header (blue for notifications, amber for reminders)
- Structured detail table (service, requester, stage info)
- "Go to Approver Dashboard" call-to-action button
- Automated footer with CITC contact info

The `NotificationMailer` service class (`app/Services/NotificationMailer.php`) is the central hub for all email dispatch. It exposes four static methods: `sendSubmitted`, `sendApprovedByL1`, `sendCompleted`, `sendRejected`.

---

## 6. Prerequisites

| Requirement | Minimum Version | How to Check |
|---|---|---|
| PHP | 8.2 | `php -v` |
| Composer | 2.x | `composer -V` |
| Node.js | 18 | `node -v` |
| npm | 9 | `npm -v` |
| SQLite | Any | `sqlite3 --version` |
| Git | Any | `git --version` |

**macOS:** Install PHP via Homebrew: `brew install php`

**Windows:** Use [Laravel Herd](https://herd.laravel.com/) or XAMPP.

---

## 7. Local Setup

Follow these steps in order from a fresh clone.

### Step 1 — Clone the repository

```bash
git clone https://github.com/Ajaysinghyadav266/citc-services.git
cd citc-services
```

### Step 2 — Install PHP dependencies

```bash
composer install
```

### Step 3 — Install Node dependencies

```bash
npm install
```

### Step 4 — Copy the environment file

```bash
cp .env.example .env
```

### Step 5 — Generate the application key

```bash
php artisan key:generate
```

### Step 6 — Configure your .env file

Open `.env` and fill in the required values. At minimum you need:
- Google OAuth credentials (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`)
- Mail/SMTP settings (`MAIL_MAILER`, `MAIL_HOST`, etc.)
- `CITC_HEAD_EMAIL` — the Dean IT email address for Level 2 approval

See the full reference in [Section 8](#8-environment-configuration).

### Step 7 — Run database migrations

```bash
php artisan migrate
```

This creates all tables including the 3-tier approval workflow columns on every request table.

### Step 8 — Build frontend assets

```bash
npm run dev
```

Keep this terminal running while developing, or run `npm run build` for a one-time build.

### Step 9 — Start the development server

```bash
php artisan serve
```

Visit: **http://127.0.0.1:8000**

---

## 8. Environment Configuration

Full reference for every environment variable used in this project:

```env
# Application
APP_NAME="CITC Services"
APP_ENV=local
APP_KEY=                            # Auto-generated by: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000       # Change to production domain in production

# Database — SQLite for local dev (no extra setup needed)
DB_CONNECTION=sqlite

# For MySQL in production:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=citc_services
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Session and Queue
SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database           # REQUIRED for async email dispatch (afterResponse)

# Mail / SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password     # Use App Password if 2FA is enabled on Gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=citc@iiti.ac.in
MAIL_FROM_NAME="CITC Services IIT Indore"

# For local testing without SMTP (emails written to storage/logs/laravel.log):
# MAIL_MAILER=log

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/login/google/callback

# CITC Custom
CITC_HEAD_EMAIL=dean_it@iiti.ac.in  # Dean IT / L2 Approver email
```

### Setting up Google OAuth

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a project and navigate to **APIs & Services > Credentials**
3. Click **Create Credentials > OAuth 2.0 Client IDs**
4. Choose **Web Application**
5. Add **Authorized Redirect URIs**:
   - `http://localhost:8000/login/google/callback` (local development)
   - `https://yourdomain.com/login/google/callback` (production)
6. Copy the **Client ID** and **Client Secret** to your `.env`

---

## 9. Database Setup

### Running Migrations

```bash
php artisan migrate
```

### Migration Files

| File | What It Creates |
|---|---|
| `create_users_table` | `users` table |
| `create_cache_table` | `cache` and `cache_locks` tables |
| `create_jobs_table` | `jobs`, `job_batches`, `failed_jobs` tables |
| `add_google_id_to_users_table` | Adds `google_id` column to users |
| `create_approvers_table` | `approvers` reference table |
| `create_vm_requests_table` | `vm_requests` table |
| `create_web_hosting_requests_table` | `web_hosting_requests` table |
| `update_vpn_requests_table` | Updates VPN schema |
| `create_internet_access_requests_table` | `internet_access_requests` table |
| `add_approval_workflow_to_request_tables` | Adds 3-tier approval columns to all four request tables |

### Approval Workflow Columns (on all request tables)

| Column | Type | Description |
|---|---|---|
| `approval_status` | enum | `pending / approved_by_1 / approved_by_2 / completed / rejected` |
| `approver_email` | string | L1 approver email fetched from ERP at submission |
| `approver_name` | string | L1 approver display name |
| `approver1_email` | string | Recorded when L1 acts |
| `approver1_name` | string | Recorded when L1 acts |
| `approved_by_1_at` | timestamp | When L1 approved |
| `approver2_email` | string | Recorded when L2 acts |
| `approver2_name` | string | Recorded when L2 acts |
| `approved_by_2_at` | timestamp | When L2 approved |
| `citc_completed_at` | timestamp | When CITC marked the request complete |
| `citc_completed_by` | string | CITC staff member who completed it |
| `rejected_by` | string | Name of who rejected |
| `rejected_by_level` | tinyInt | Level that rejected (1, 2, or 3) |
| `rejection_reason` | text | Reason provided at rejection |
| `rejected_at` | timestamp | Timestamp of rejection |

### Reset the Database (local dev only)

```bash
php artisan migrate:fresh
```

Warning: this drops all tables and data. Never run in production.

---

## 10. Running the Application

### Development Mode

You need two terminals running simultaneously:

**Terminal 1 — PHP Server:**
```bash
php artisan serve
```

**Terminal 2 — Vite (hot-reload for frontend assets):**
```bash
npm run dev
```

Visit: **http://127.0.0.1:8000**

### One-Time Production Build

```bash
npm run build
```

---

## 11. Approver Role System

Approver levels are **automatically detected** after Google OAuth, based on the logged-in email and ERP department data.

### Detection Logic

The `ApproverController::detectApproverLevel(string $email)` method runs on every approver login:

1. If email matches `CITC_HEAD_EMAIL` (Dean IT) → **Level 2**
2. Otherwise, call the ERP API:
   - If the email is not found in ERP → **Level 0** (Access Denied, redirected away)
   - If the user's department contains "Computer and Information Technology Center" → **Level 3**
   - Any other valid ERP staff or faculty → **Level 1**

### Level Permissions

| Level | Role | What They See | What They Can Do |
|---|---|---|---|
| **L1** | Faculty / Staff Approver | Their own assigned pending, approved, rejected requests | Approve or Reject |
| **L2** | Dean IT | Requests that have been approved by L1 | Approve or Reject |
| **L3** | CITC Team | **All requests** across all four services and all statuses | Mark Complete, Delete, View |

### Approver Login Flow

1. Approver visits `/approver-login`
2. Clicks "Sign in with Google"
3. OAuth callback triggers `detectApproverLevel()`
4. Level is stored in the session (`approver_level`)
5. Approver is redirected to `/approver/dashboard`
6. Dashboard and all subsequent pages adapt based on the stored level

---

## 12. Artisan Commands and Scheduler

### Reminder Command

Send reminder emails to approvers for requests that have been pending too long:

```bash
# Default: remind for requests pending more than 24 hours
php artisan citc:send-reminders

# Custom threshold: remind for requests pending more than 48 hours
php artisan citc:send-reminders --hours=48

# Local testing: remind for ALL pending requests (bypass time check)
php artisan citc:send-reminders --hours=0
```

**Example output:**
```
  [L1 reminder] VPN #3 → stest@iiti.ac.in
  [L2 reminder] Internet Access #7 → ftest@iiti.ac.in
Done. Sent 1 L1 reminder(s) and 1 L2 reminder(s).
```

Scheduler output is appended to: `storage/logs/reminders.log`

### Automated Scheduler

The reminder command is registered in `routes/console.php` to run every hour:

```php
Schedule::command('citc:send-reminders --hours=24')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/reminders.log'));
```

### Activating the Scheduler on a Server

Add exactly one line to your server's crontab (`crontab -e`):

```
* * * * * cd /path/to/citc-services && php artisan schedule:run >> /dev/null 2>&1
```

The system cron fires every minute; Laravel's scheduler checks if any registered commands are due and runs them (in this case, hourly).

### Other Useful Commands

```bash
# See all registered scheduled tasks
php artisan schedule:list

# Run the scheduler immediately (useful for testing)
php artisan schedule:run

# Clear config cache after changing .env
php artisan config:clear

# Clear all application caches
php artisan cache:clear

# See all available artisan commands
php artisan list
```

---

## 13. Testing the Email System Locally

### Option A — Log Driver (simplest, no SMTP needed)

Set in `.env`:
```env
MAIL_MAILER=log
```

All emails are written to `storage/logs/laravel.log`. Watch them in real time:

```bash
tail -f storage/logs/laravel.log
```

### Option B — Mailtrap (view formatted emails in a browser)

1. Sign up free at [mailtrap.io](https://mailtrap.io)
2. Go to **Email Testing > Inboxes > SMTP Settings**
3. Add the credentials to `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<your_mailtrap_username>
MAIL_PASSWORD=<your_mailtrap_password>
MAIL_ENCRYPTION=tls
```

No real emails are sent. Everything appears in the Mailtrap dashboard as a formatted preview.

### Testing Reminders Locally

Your local test requests will not be 24+ hours old. Use `--hours=0` to bypass the time filter and treat all pending requests as stale:

```bash
php artisan citc:send-reminders --hours=0
```

---

## 14. Project Structure

```
citc-services/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SendPendingReminders.php     # Artisan command: citc:send-reminders
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ApproverController.php       # Level detection, approve, reject, delete
│   │       ├── AuthController.php           # Google OAuth login/logout (user + approver)
│   │       ├── InternetAccessController.php # Internet Access request form + store
│   │       ├── MyRequestsController.php     # User's "My Requests" history page
│   │       ├── VmRequestController.php      # VM Machine request form + store
│   │       ├── VpnController.php            # VPN request form + ERP approver lookup
│   │       └── WebHostingRequestController.php
│   ├── Models/
│   │   ├── Approver.php
│   │   ├── InternetAccessRequest.php
│   │   ├── User.php
│   │   ├── VmRequest.php
│   │   ├── VpnRequest.php
│   │   └── WebHostingRequest.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/
│       └── NotificationMailer.php           # Central email service (all 4 events)
├── database/
│   └── migrations/                          # All 10 migration files
├── resources/
│   └── views/
│       ├── approver/
│       │   ├── all-requests.blade.php       # L3 admin: all requests across all services
│       │   ├── dashboard.blade.php
│       │   ├── pending.blade.php
│       │   ├── approved.blade.php
│       │   └── rejected.blade.php
│       ├── layouts/
│       │   └── approver.blade.php           # Shared navbar layout for approver pages
│       ├── dashboard.blade.php              # User dashboard with service tiles
│       ├── welcome.blade.php                # User login/landing page
│       ├── home.blade.php                   # Public home page
│       ├── vpn-form.blade.php
│       ├── internet-access.blade.php
│       ├── vm-request.blade.php
│       └── web-host.blade.php
├── routes/
│   ├── web.php                              # All HTTP routes
│   └── console.php                          # Scheduler registration
├── docs/
│   └── internet_access_request_prompt.md
├── .env                                     # Local config (not committed to git)
├── .env.example                             # Template — copy this to .env
└── README.md
```

---

## 15. Database Schema

### Request Tables

All four request tables (`vpn_requests`, `internet_access_requests`, `vm_requests`, `web_hosting_requests`) share the approval workflow columns listed in Section 9. The core columns differ slightly per service type but all include at minimum:

| Column | Description |
|---|---|
| `id` | Auto-increment primary key |
| `name` / `owner_name` | Requester's full name |
| `email` / `institute_email` | Requester's email |
| `approver_email` | L1 approver email (fetched from ERP at submission time) |
| `approver_name` | L1 approver name |
| `approval_status` | Current workflow status enum |
| `created_at` | When the request was submitted |
| *(all approval workflow columns)* | See Section 9 for full list |

---

## 16. Routes Reference

### User-Facing Routes

| Method | Path | Description |
|---|---|---|
| GET | `/` | Landing / home page |
| GET | `/login` | User login page |
| GET | `/login/google` | Redirect to Google OAuth (user flow) |
| GET | `/login/google/callback` | OAuth callback — authenticates user |
| POST | `/logout` | Log out |
| GET | `/dashboard` | User dashboard (requires auth) |
| GET | `/my-requests` | User's full request history |
| GET | `/vpn-form` | VPN request form |
| POST | `/vpn-submit` | Submit VPN request |
| GET | `/internet-access` | Internet access form |
| POST | `/internet-access` | Submit internet access request |
| GET | `/vm-request-application/new` | VM machine request form |
| POST | `/vm-request-application` | Submit VM request |
| GET | `/web-host` | Web hosting form |
| POST | `/submit` | Submit web hosting request |
| GET | `/get-approver` | ERP API proxy — returns approver for a given email |

### Approver Routes (prefix: `/approver/`, requires auth)

| Method | Path | Access Level | Description |
|---|---|---|---|
| GET | `/approver-login` | Public | Approver login page |
| GET | `/approver-login/google` | Public | Redirect to Google OAuth (approver flow) |
| GET | `/approver/dashboard` | L1, L2, L3 | Approver dashboard |
| GET | `/approver/pending` | L1, L2 | Pending requests queue |
| GET | `/approver/approved` | L1, L2 | Approved requests list |
| GET | `/approver/rejected` | L1, L2 | Rejected requests list |
| GET | `/approver/citc/pending` | L3 | L2-approved, awaiting CITC fulfillment |
| GET | `/approver/citc/completed` | L3 | CITC-completed requests |
| GET | `/approver/all-requests` | L3 | All requests across all services and statuses |
| POST | `/approver/approve/{type}/{id}` | L1, L2 | Approve a request |
| POST | `/approver/reject/{type}/{id}` | L1, L2, L3 | Reject a request with a reason |
| DELETE | `/approver/delete/{type}/{id}` | L3 | Permanently delete a request |

`{type}` accepted values: `vpn`, `internet`, `vm`, `hosting`

---

## 17. Deployment Checklist

```bash
# 1. Pull latest code
git pull origin main

# 2. Install PHP dependencies (no dev packages)
composer install --no-dev --optimize-autoloader

# 3. Build frontend assets
npm ci && npm run build

# 4. Set up environment
cp .env.example .env
# Edit .env: set APP_ENV=production, APP_DEBUG=false, APP_URL, DB credentials, SMTP, Google OAuth

# 5. Generate app key (if first deployment)
php artisan key:generate

# 6. Run database migrations
php artisan migrate --force

# 7. Cache config, routes, and views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Set correct file permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 9. Add the scheduler cron job
crontab -e
# Paste this line:
# * * * * * cd /var/www/citc-services && php artisan schedule:run >> /dev/null 2>&1
```

### Production .env Differences

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=citc_services
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

QUEUE_CONNECTION=database     # Keep this — required for async emails without Redis
MAIL_MAILER=smtp              # Must be smtp in production, not log

GOOGLE_REDIRECT_URI=https://yourdomain.com/login/google/callback
```

**Never commit your `.env` file.** It is listed in `.gitignore` and contains secrets.

---

## 18. Contributing

### Branching Strategy

- `main` — stable, production-ready code
- `feature-*` — all new work goes in feature branches

### Workflow

```bash
# Create your feature branch
git checkout -b feature/your-feature-name

# Make and commit your changes
git add .
git commit -m "short description of what changed"

# Push to remote
git push origin feature/your-feature-name

# Open a Pull Request on GitHub to merge into main
```

### After a PR is Merged

Update your local `main`:

```bash
git checkout main
git pull --rebase origin main
```

### Set Your Git Identity

Make sure your commits are credited to your GitHub account:

```bash
git config --global user.name "Your Name"
git config --global user.email "your_verified_github_email@example.com"
```

The email must match a verified email address on your GitHub profile for commits to appear in your contribution graph.

---

## Team

| Name | Contributions |
|---|---|
| **Aryavrat Mishra** | Google Auth, Approval workflow, Email pipeline (`NotificationMailer`), Async mail dispatch, Reminder scheduler (`SendPendingReminders`), All-Requests L3 admin view, VM/Web Hosting approval routing fix |
| **Ajaysinghyadav266** | Project initialisation, VPN module, ERP API integration |
| **Aakash Singh** | Test branch contributions, PR reviews |
| **Rohit Pawar** | Web Hosting module |

---

*This project is maintained by the CITC team at IIT Indore.*
*For queries, contact the CITC helpdesk.*
