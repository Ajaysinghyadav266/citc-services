{{--
    View: New VM Request Application
    Path in Laravel app: resources/views/vm-request/create.blade.php
    Expects (optional): $departmentName, $ownerName, $ownerEmail from the controller
    Controller: App\Http\Controllers\VmRequestController@create / @store
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New VM Request Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/vm-request.css', 'resources/js/vm-request.js'])
</head>

<body class="vmreq-body">
    <!-- Navbar -->
<nav class="vm-navbar">
    <div class="vm-navbar-left">
        <a href="{{ url('/') }}" class="vm-logo">
            <span class="logo-circle">VM</span>
            <span class="logo-text">VM Request Portal</span>
        </a>
    </div>

    <div class="vm-navbar-right">
        @auth
            <div class="profile-dropdown">
                <button type="button" class="profile-btn" id="profileBtn">
                    <span class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}
                    </span>
                    <span class="profile-name">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="arrow">&#9662;</span>
                </button>

                <div class="dropdown-menu" id="profileMenu">
                    <a href="{{ url('/about') }}">
                        <i class="fa-solid fa-circle-info"></i>
                        About
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
           <a href="{{ route('login.google') }}" class="login-btn">
                Login
            </a>
        @endauth
    </div>
</nav>
<div class="vmreq-shell">



<div class="vmreq-shell">

    <div class="vmreq-card">

        <div class="vmreq-titlebar">
            <h1>New VM Request Application
                <span class="vmreq-status" id="vmreqStatus">• Not Saved</span>
            </h1>
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
    }, 2500);   // Redirect after 2.5 seconds
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

</body>
</html>