{{--
    View: New VM Request Application
    Path in Laravel app: resources/views/vm-requests.blade.php
    Controller: App\Http\Controllers\VmRequestController@create / @store
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Virtual Machine Request | IIT Indore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind:wght@400;500;600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Outfit', sans-serif; }
        .hindi-text { font-family: 'Hind', sans-serif; }
        .nav-link { transition: background .18s, color .18s; border-radius: 9999px; }
        .nav-link.active { background: #1e3a8a; color: #fff !important; }
        .nav-link:hover:not(.active) { background: #e2e8f0; }
    </style>
    <style>
/* ==========================================================================
   VM Request Application — stylesheet
   Path in Laravel app: resources/css/vm-request.css
   ========================================================================== */

:root {
    --ink: #1b2430;
    --ink-soft: #5b6675;
    --line: #d9dee5;
    --line-soft: #eceff3;
    --surface: #ffffff;
    --canvas: #f3f5f8;
    --band: #e7ecf5;
    --band-text: #2f4270;
    --brand: #2f5fb3;
    --brand-dark: #234a91;
    --brand-soft: #eaf0fb;
    --danger: #c4342f;
    --danger-soft: #fbe9e8;
    --ok: #1f7a4d;
    --radius: 6px;
    --font: "Segoe UI", "Inter", Roboto, Helvetica, Arial, sans-serif;
}

* {
    box-sizing: border-box;
}
.vm-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 32px;
    background: #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.vm-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}

.logo-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #2563eb;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 700;
}

.logo-text {
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
}

.profile-dropdown {
    position: relative;
}

.profile-btn {
    border: none;
    background: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    padding: 8px 14px;
    border-radius: 10px;
    transition: 0.25s;
}

.profile-btn:hover {
    background: #f3f4f6;
}

.profile-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.profile-name {
    font-weight: 500;
    color: #374151;
}

.dropdown-menu {
    position: absolute;
    right: 0;
    top: 60px;
    width: 180px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    display: none;
    overflow: hidden;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-menu a,
.dropdown-menu button {
    width: 100%;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: none;
    background: none;
    text-decoration: none;
    color: #374151;
    cursor: pointer;
    font-size: 15px;
}

.dropdown-menu a:hover,
.dropdown-menu button:hover {
    background: #f5f5f5;
}

.login-btn {
    background: #2563eb;
    color: #fff;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
}

.login-btn:hover {
    background: #1d4ed8;
}
body.vmreq-body {
    margin: 0;
    font-family: var(--font);
    background: var(--canvas);
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
}

.vmreq-shell {
    max-width: 960px;
    margin: 0 auto;
    padding: 24px 16px 64px;
}

/* Breadcrumb / top bar --------------------------------------------------- */
.vmreq-crumbs {
    font-size: 13px;
    color: var(--ink-soft);
    margin-bottom: 10px;
}
.vmreq-crumbs span + span::before {
    content: "›";
    margin: 0 6px;
    color: var(--line);
}

.vmreq-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(20, 30, 50, 0.04);
}

.vmreq-titlebar {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 25px 20px;
    min-height: 100px;
}
.vmreq-titlebar h1 {
    font-size: 25px;
    margin: 0;
    font-weight: 600;
    color: #1d4ed8;
}
.vmreq-titlebar p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #9ca3af;
}
.vmreq-status {
    font-size: 12px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 999px;
    background: var(--danger-soft);
    color: var(--danger);
    letter-spacing: 0.2px;
}
.vmreq-status.is-saved {
    background: #e5f4ea;
    color: var(--ok);
}

.btn {
    font: inherit;
    font-size: 14px;
    font-weight: 600;
    padding: 9px 20px;
    border-radius: var(--radius);
    border: 1px solid transparent;
    cursor: pointer;
    transition:
        background 0.15s ease,
        box-shadow 0.15s ease,
        opacity 0.15s ease;
}
.btn-primary {
    background: var(--brand);
    color: #fff;
}
.btn-primary:hover {
    background: var(--brand-dark);
}
.btn-primary:disabled {
    background: #9db3d8;
    cursor: not-allowed;
}
.btn-primary:focus-visible,
.btn:focus-visible,
input:focus-visible,
select:focus-visible,
textarea:focus-visible {
    outline: 3px solid #9fc0f0;
    outline-offset: 1px;
}

/* Section band ------------------------------------------------------------ */
.vmreq-band {
    background: var(--band);
    color: var(--band-text);
    text-align: center;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.4px;
    padding: 10px 12px;
    margin: 0;
}

.vmreq-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 28px;
    padding: 24px 24px 8px;
}
.vmreq-fields.is-tight {
    padding-top: 20px;
}

@media (max-width: 720px) {
    .vmreq-fields {
        grid-template-columns: 1fr;
    }
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
}
.field.span-2 {
    grid-column: 1 / -1;
}

.field label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
}
.field label .req {
    color: var(--danger);
    margin-left: 2px;
}

.field .hint {
    font-size: 12px;
    color: var(--ink-soft);
}

.field input[type="text"],
.field input[type="email"],
.field input[type="tel"],
.field input[type="number"],
.field input[type="date"],
.field select,
.field textarea {
    font: inherit;
    font-size: 14px;
    padding: 9px 11px;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    background: var(--surface);
    color: var(--ink);
    width: 100%;
}
.field textarea {
    resize: vertical;
    min-height: 78px;
}

.field input:disabled,
.field input[readonly] {
    background: var(--line-soft);
    color: var(--ink-soft);
}

.field select {
    appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8'><path d='M1 1l5 5 5-5' fill='none' stroke='%235b6675' stroke-width='1.6'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

.field.is-invalid input,
.field.is-invalid select,
.field.is-invalid textarea {
    border-color: var(--danger);
    background: var(--danger-soft);
}

.field-error {
    font-size: 12px;
    color: var(--danger);
    min-height: 14px;
    display: none;
}
.field.is-invalid .field-error {
    display: block;
}

/* Footer notice ------------------------------------------------------------ */
.vmreq-notice {
    margin: 8px 24px 0;
    padding: 12px 14px;
    background: var(--danger-soft);
    border: 1px solid #f0c9c7;
    border-radius: var(--radius);
    color: #8a2622;
    font-size: 12.5px;
    line-height: 1.5;
}

.vmreq-confirm {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin: 16px 24px 0;
    font-size: 13px;
}
.vmreq-confirm input {
    margin-top: 3px;
    width: 16px;
    height: 16px;
}
.vmreq-confirm.is-invalid {
    color: var(--danger);
}

.vmreq-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 22px 24px 26px;
}

/* Toast / Snackbar */
.vmreq-toast {
    position: fixed;
    right: 24px;
    bottom: 24px;
    min-width: 330px;
    max-width: 420px;

    padding: 16px 22px;

    color: #fff;
    font-size: 14px;
    font-weight: 500;

    border-radius: 18px;

    backdrop-filter: blur(18px);

    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);

    opacity: 0;
    transform: translateY(40px);

    transition: 0.35s ease;

    z-index: 9999;
}

.vmreq-toast.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* Loading */
.vmreq-toast.is-loading {
    background: rgba(255, 255, 255, 0.95);
    color: #1f2937;

    border: 1px solid #e5e7eb;

    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);

    backdrop-filter: blur(16px);
}
/* Success */
.vmreq-toast.is-success {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
}
/* Error */
.vmreq-toast.is-error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
}
</style>
</head>

<body class="vmreq-body">

<!-- SHARED NAVBAR -->
<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <div style="max-width:1280px;margin:0 auto;padding:0 1rem;display:flex;align-items:center;justify-content:space-between;height:70px;">

        <!-- LEFT: IIT Indore Identity -->
        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
            <a href="{{ session('approver_level') ? route('approver.dashboard') : '/dashboard' }}">
                <img src="{{ asset('logo.png') }}"
                     alt="IIT Indore Emblem" style="height:48px;width:auto;">
            </a>
            <div style="width:1px;height:40px;background:#BF7771;margin:0 4px;"></div>
            <div style="line-height:1.2;">
                <p class="hindi-text" style="font-size:13px;font-weight:500;color:#111827;margin:0;">भारतीय प्रौद्योगिकी संस्थान इंदौर</p>
                <p style="font-size:11px;font-weight:300;color:#6b7280;margin:0;">Indian Institute of Technology Indore</p>
            </div>
        </div>

        <!-- CENTRE: Pill navigation -->
        <div style="display:flex;align-items:center;">
            <div style="display:flex;align-items:center;gap:4px;background:#f1f5f9;border-radius:9999px;padding:6px 8px;">
                <a href="{{ session('approver_level') ? route('approver.dashboard') : '/dashboard' }}" class="nav-link" style="font-size:12.5px;font-weight:600;color:#374151;padding:6px 16px;">Home</a>
                <a href="/vpn-form" class="nav-link" style="font-size:12.5px;font-weight:600;color:#374151;padding:6px 16px;">VPN</a>
                <a href="/internet-access" class="nav-link" style="font-size:12.5px;font-weight:600;color:#374151;padding:6px 16px;">Internet Access</a>
                <a href="/vm-request-application/new" class="nav-link active" style="font-size:12.5px;font-weight:600;padding:6px 16px;">Virtual Machine</a>
                <a href="/web-host" class="nav-link" style="font-size:12.5px;font-weight:600;color:#374151;padding:6px 16px;">Web Hosting</a>
                <a href="/my-requests" class="nav-link" style="font-size:12.5px;font-weight:600;color:#374151;padding:6px 16px;">My Requests</a>
            </div>
        </div>

        <!-- RIGHT: User + Logout -->
        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:32px;height:32px;border-radius:50%;background:#1e3a8a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span style="font-size:13px;font-weight:500;color:#1f2937;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ auth()->user()->name }}
                </span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" style="font-size:12px;font-weight:600;color:#6b7280;border:1px solid #d1d5db;padding:6px 12px;border-radius:9999px;background:none;cursor:pointer;transition:color .15s,border-color .15s;">
                    Logout
                </button>
            </form>
        </div>

    </div>
</nav>
<!-- END SHARED NAVBAR -->



<div class="vmreq-shell">

    <div class="vmreq-card">

        <div class="vmreq-titlebar">
            <h1>New VM Request Application</h1>
            <p>Indian Institute of Technology Indore — CITC</p>
        </div>

        <form id="vmRequestForm"
              action="{{ route('vm-requests.store') }}"
              method="POST"
              novalidate>
            @csrf

            <h2 class="vmreq-band">Requester Details</h2>
            <div class="vmreq-fields">

                <div class="field">
                    <label for="institute_email">Institute Email ID of the Owner<span class="req">*</span></label>
                    <input type="email" id="institute_email" name="institute_email"
                           value="{{ old('institute_email', $ownerEmail ?? '') }}"
                           readonly>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="department_name">Department/Section/Centre Name<span class="req">*</span></label>
                    <input type="text" id="department_name" name="department_name"
                           value="{{ old('department_name', $departmentName ?? '') }}"
                           placeholder="e.g. Computer And Information Technology Center (CITC)" required>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="owner_name">Name of Owner<span class="req">*</span></label>
                    <input type="text" id="owner_name" name="owner_name"
                           value="{{ old('owner_name', $ownerName ?? '') }}"
                            readonly>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="mobile_number">Mobile Number of the User<span class="req">*</span></label>
                    <input type="tel" id="mobile_number" name="mobile_number"
                           value="{{ old('mobile_number') }}"
                           placeholder="10-digit mobile number" maxlength="10" required>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="employee_category">Employee category<span class="req">*</span></label>
                    <select id="employee_category" name="employee_category" required>
                        <option value="" selected disabled>Select category</option>
                        <option value="faculty" {{ old('employee_category') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                        <option value="staff" {{ old('employee_category') === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="research_scholar" {{ old('employee_category') === 'research_scholar' ? 'selected' : '' }}>Research Scholar</option>
                        <option value="student" {{ old('employee_category') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="other" {{ old('employee_category') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <span class="field-error"></span>
                </div>

            </div>

            <h2 class="vmreq-band">Approver Details</h2>
            <div class="vmreq-fields">

                <div class="field">
                    <label for="approver_email">Approver Email<span class="req">*</span></label>
                    <input type="email" id="approver_email" name="approver_email"
                           value="{{ old('approver_email') }}"
                           placeholder="approver@iiti.ac.in" required>
                    <span class="hint">Tab out after typing to auto-fill approver details.</span>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="approver_name">Approver Name</label>
                    <input type="text" id="approver_name" name="approver_name"
                           value="{{ old('approver_name') }}"
                           placeholder="Auto-filled from ERP" readonly>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="approver_designation">Designation</label>
                    <input type="text" id="approver_designation" name="approver_designation"
                           value="{{ old('approver_designation') }}"
                           placeholder="Auto-filled from ERP" readonly>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="approver_department">Department</label>
                    <input type="text" id="approver_department" name="approver_department"
                           value="{{ old('approver_department') }}"
                           placeholder="Auto-filled from ERP" readonly>
                    <span class="field-error"></span>
                </div>

            </div>

            <h2 class="vmreq-band">VM Details</h2>
            <div class="vmreq-fields is-tight">

                <div class="field">
                    <label for="operating_system">Operating System<span class="req">*</span></label>
                    <select id="operating_system" name="operating_system" required>
                        <option value="" selected disabled>Select OS</option>
                        <option value="windows_server_2019">Windows Server 2019</option>
                        <option value="windows_server_2022">Windows Server 2022</option>
                        <option value="ubuntu_20_04">Ubuntu 20.04 LTS</option>
                        <option value="ubuntu_22_04">Ubuntu 22.04 LTS</option>
                        <option value="centos_7">CentOS 7</option>
                        <option value="rocky_linux">Rocky Linux</option>
                        <option value="debian">Debian</option>
                        <option value="other">Other</option>
                    </select>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="vm_expiry_date">VM Expiry Date<span class="req">*</span></label>
                    <input type="date" id="vm_expiry_date" name="vm_expiry_date" required>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="os_type">OS Type<span class="req">*</span></label>
                    <select id="os_type" name="os_type" required>
                        <option value="" selected disabled>Select type</option>
                        <option value="32_bit">32-bit</option>
                        <option value="64_bit">64-bit</option>
                    </select>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="purpose_usage">Purpose(s) of the usage of VM<span class="req">*</span></label>
                    <textarea id="purpose_usage" name="purpose_usage" rows="3" required></textarea>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="cpu_cores">CPU (No. of Cores)<span class="req">*</span></label>
                    <input type="number" id="cpu_cores" name="cpu_cores" min="1" max="64" required>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="ram_gb">RAM (GB)<span class="req">*</span></label>
                    <input type="number" id="ram_gb" name="ram_gb" min="1" max="512" required>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="justification">Justification of resources (RAM, CPU, Hard Disk, etc.) usage<span class="req">*</span></label>
                    <textarea id="justification" name="justification" rows="3" required></textarea>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="hard_disk_gb">Hard Disk (GB)<span class="req">*</span></label>
                    <input type="number" id="hard_disk_gb" name="hard_disk_gb" min="1" max="10000" required>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="license_type">Software's/Packages license type to be installed in VM<span class="req">*</span></label>
                    <select id="license_type" name="license_type" required>
                        <option value="" disabled>Select license type</option>
                        <option value="open_source" selected>Open Source</option>
                        <option value="licensed">Licensed</option>
                        <option value="freeware">Freeware</option>
                    </select>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="sub_domain">Sub Domain</label>
                    <input type="text" id="sub_domain" name="sub_domain" placeholder="e.g. app.institute.ac.in">
                    <span class="hint">Optional. Leave blank if not required.</span>
                    <span class="field-error"></span>
                </div>

                <div class="field span-2">
                    <label for="software_list">List out the Software/Packages to be installed in the VM<span class="req">*</span></label>
                    <textarea id="software_list" name="software_list" rows="3" required></textarea>
                    <span class="field-error"></span>
                </div>

                <div class="field">
                    <label for="ssl_configuration">SSL Configuration<span class="req">*</span></label>
                    <select id="ssl_configuration" name="ssl_configuration" required>
                        <option value="" selected disabled>Select</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <span class="field-error"></span>
                </div>

            </div>

            <p class="vmreq-notice">
                The VM resources you have requested are not guaranteed to be allocated. Allocation will depend
                on the available resources within the CITC Data Center. CITC reserves the right to reduce the
                resource allocation as necessary.
            </p>

            <label class="vmreq-confirm" id="confirmWrap" for="i_confirm">
                <input type="checkbox" id="i_confirm" name="i_confirm" value="1">
                <span>I Confirm the above details are correct and I accept the allocation policy.</span>
            </label>

            <div class="vmreq-actions">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

        </form>
    </div>
</div>

<div class="vmreq-toast" id="vmreqToast"></div>
@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    showToast("{{ session('success') }}", "success");
    setTimeout(function () {
        window.location.href = "{{ route('dashboard') }}";
    }, 1000);
});
</script>
@endif
@if(session('error'))
<script>
setTimeout(() => {
    showToast("{{ session('error') }}", "error");
}, 300);
</script>
@endif

<script>
// ── Auto-populate approver details from ERP API ──
document.getElementById('approver_email').addEventListener('blur', function () {
    const email = this.value.trim();
    if (!email) return;

    fetch(`/get-approver?email=${encodeURIComponent(email)}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('approver_name').value        = data.name        || '';
            document.getElementById('approver_designation').value = data.designation || '';
            document.getElementById('approver_department').value  = data.department  || '';
        })
        .catch(err => console.error('Approver lookup failed:', err));
});

// ── Validate approver before submit ──
document.getElementById('vmRequestForm').addEventListener('submit', function (e) {
    const name        = document.getElementById('approver_name').value.trim();
    const designation = document.getElementById('approver_designation').value.trim();
    const department  = document.getElementById('approver_department').value.trim();

    if (!name || !designation || !department) {
        e.preventDefault();
        // alert('Please enter a valid approver email and wait for their details to auto-fill before submitting.');
    }
});
</script>

<script>
/* ==========================================================================
   VM Request Application — client-side validation
   Path in Laravel app: resources/js/vm-request.js
   Mirrors the server-side rules in App\Http\Controllers\VmRequestController
   ========================================================================== */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", init);

  function init() {
    var form = document.getElementById("vmRequestForm");
    if (!form) return;

    var statusPill  = document.getElementById("vmreqStatus");
    var confirmWrap = document.getElementById("confirmWrap");
    var confirmBox  = document.getElementById("i_confirm");
    var saveBtn     = document.getElementById("saveBtn");
    var toast       = document.getElementById("vmreqToast");

    // Declarative validation rules per field name.
    var rules = {
      institute_email: {
        required: true,
        test: function (v) { return /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/.test(v); },
        message: "Enter a valid institute email address."
      },
      department_name: {
        required: true,
        message: "Department / Section / Centre name is required."
      },
      owner_name: {
        required: true,
        test: function (v) { return v.trim().length >= 3; },
        message: "Enter the full name of the owner (min 3 characters)."
      },
      mobile_number: {
        required: true,
        test: function (v) { return /^[6-9]\d{9}$/.test(v); },
        message: "Enter a valid 10-digit mobile number."
      },
      employee_category: {
        required: true,
        message: "Select an employee category."
      },
      operating_system: {
        required: true,
        message: "Select an operating system."
      },
      vm_expiry_date: {
        required: true,
        test: function (v) {
          var picked = new Date(v + "T00:00:00");
          var today  = new Date(); today.setHours(0, 0, 0, 0);
          return !isNaN(picked) && picked > today;
        },
        message: "Choose a valid expiry date in the future."
      },
      os_type: {
        required: true,
        message: "Select an OS type."
      },
      purpose_usage: {
        required: true,
        test: function (v) { return v.trim().length >= 5; },
        message: "Describe the purpose in at least 5 characters."
      },
      cpu_cores: {
        required: true,
        test: function (v) { return Number(v) >= 1 && Number(v) <= 64; },
        message: "Cores must be a number between 1 and 64."
      },
      ram_gb: {
        required: true,
        test: function (v) { return Number(v) >= 1 && Number(v) <= 512; },
        message: "RAM must be a number between 1 and 512 GB."
      },
      justification: {
        required: true,
        test: function (v) { return v.trim().length >= 5; },
        message: "Provide a justification of at least 5 characters."
      },
      hard_disk_gb: {
        required: true,
        test: function (v) { return Number(v) >= 1 && Number(v) <= 10000; },
        message: "Hard disk must be a number between 1 and 10000 GB."
      },
      license_type: {
        required: true,
        message: "Select a license type."
      },
      software_list: {
        required: true,
        test: function (v) { return v.trim().length >= 3; },
        message: "List at least one software/package to install."
      },
      sub_domain: {
        required: false,
        test: function (v) { return v === "" || /^[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/.test(v); },
        message: "Enter a valid sub domain (letters, numbers, dots, hyphens)."
      },
      ssl_configuration: {
        required: true,
        message: "Select whether SSL configuration is required."
      }
    };

    // Wire up live validation.
    Object.keys(rules).forEach(function (name) {
      var input = form.elements[name];
      if (!input) return;
      var evt = (input.tagName === "SELECT") ? "change" : "input";
      input.addEventListener(evt, function () { validateField(name); });
      input.addEventListener("blur", function () { validateField(name); });
    });

    confirmBox.addEventListener("change", function () {
      confirmWrap.classList.remove("is-invalid");
    });

    form.addEventListener("submit", function (e) {
      setUnsaved();

      var allValid = true;
      Object.keys(rules).forEach(function (name) {
        if (!validateField(name)) allValid = false;
      });

      if (!confirmBox.checked) {
        confirmWrap.classList.add("is-invalid");
        allValid = false;
      }

      if (!allValid) {
        e.preventDefault();
        showToast("Please fix the highlighted fields.", "error");
        var firstError = form.querySelector(".field.is-invalid, .vmreq-confirm.is-invalid");
        if (firstError) firstError.scrollIntoView({ behavior: "smooth", block: "center" });
        return;
      }

      // Valid: let the browser POST natively to VmRequestController@store
      // (Laravel handles CSRF + authoritative server-side validation there).
      document.querySelectorAll('button[type="submit"]').forEach(function(btn){
      btn.disabled = true;
      btn.innerHTML = "Saving...";
    });

showToast("⏳ Saving your VM Request...", "loading");
    });

    function validateField(name) {
      var rule  = rules[name];
      var input = form.elements[name];
      if (!rule || !input) return true;

      var fieldWrap = input.closest(".field");
      var errorEl   = fieldWrap ? fieldWrap.querySelector(".field-error") : null;
      var value     = (input.value || "").trim();

      var isValid = true;
      var message = "";

      if (rule.required && value === "") {
        isValid = false;
        message = rule.message || "This field is required.";
      } else if (value !== "" && rule.test && !rule.test(value)) {
        isValid = false;
        message = rule.message || "This value is not valid.";
      }

      if (fieldWrap) {
        fieldWrap.classList.toggle("is-invalid", !isValid);
      }
      if (errorEl) {
        errorEl.textContent = isValid ? "" : message;
      }

      return isValid;
    }

    function setUnsaved() {
      statusPill.textContent = "• Not Saved";
      statusPill.classList.remove("is-saved");
    }
  }
   window.showToast = function(message, type) {

      var toast = document.getElementById("vmreqToast");

      toast.textContent = message;

      toast.className = "vmreq-toast";

      if(type === "success"){
          toast.classList.add("is-success");
      }
      else if(type === "error"){
          toast.classList.add("is-error");
      }
      else{
          toast.classList.add("is-loading");
      }

      toast.classList.add("is-visible");

      if(type !== "loading"){
          clearTimeout(window.showToast._t);

          window.showToast._t = setTimeout(function () {
              toast.classList.remove("is-visible");
          },3000);
      }
  };
})();

</script>
</body>
</html>