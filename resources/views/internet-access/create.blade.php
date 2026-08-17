@extends('layouts.dashboard')

@section('title', 'Internet Access Request')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold text-center text-blue-700 mb-2">
        REQUEST FOR INTERNET ACCESS
    </h1>
    <p class="text-center text-gray-400 text-sm mb-6">Indian Institute of Technology Indore — CITC</p>

    @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 text-sm">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc ml-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="internetForm" action="{{ route('internet-access.store') }}" method="POST">
    @csrf

    <!-- SECTION 1: Personal & Academic Information -->
    <h2 class="text-base font-semibold text-gray-700 mb-3">Personal &amp; Academic Information</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="text-sm font-medium text-gray-600">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required {{ auth()->check() ? 'readonly' : '' }}
                class="w-full border {{ auth()->check() ? 'border-gray-200 bg-gray-50 text-gray-500' : 'border-gray-300' }} rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required {{ auth()->check() ? 'readonly' : '' }}
                class="w-full border {{ auth()->check() ? 'border-gray-200 bg-gray-50 text-gray-500' : 'border-gray-300' }} rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Roll No / Employee ID <span class="text-red-500">*</span></label>
            <input type="text" name="roll_no" value="{{ old('roll_no') }}" required
                placeholder="e.g. 2301101001 or E1234"
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Phone Number <span class="text-red-500">*</span></label>
            <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="10"
                placeholder="10-digit mobile number"
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

    </div>

    <!-- SECTION 2: Approver Details -->
    <h2 class="text-base font-semibold text-gray-700 mt-6 mb-3">Approver Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="text-sm font-medium text-gray-600">Approver Email <span class="text-red-500">*</span></label>
            <input type="email" id="approver_email" name="approver_email"
                value="{{ old('approver_email') }}"
                placeholder="approver@iiti.ac.in"
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Approver Name</label>
            <input type="text" id="approver_name" name="approver_name"
                value="{{ old('approver_name') }}" readonly
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-sm mt-1">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Designation</label>
            <input type="text" id="approver_designation" name="approver_designation"
                value="{{ old('approver_designation') }}" readonly
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-sm mt-1">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Department</label>
            <input type="text" id="approver_department" name="approver_department"
                value="{{ old('approver_department') }}" readonly
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-sm mt-1">
        </div>

    </div>

    <!-- SECTION 3: Hardware & Technical Profile -->
    <h2 class="text-base font-semibold text-gray-700 mt-6 mb-3">Hardware &amp; Technical Profile</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="text-sm font-medium text-gray-600">Device Type <span class="text-red-500">*</span></label>
            <select name="device_type" required
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">Select device type</option>
                <option value="Laptop"  {{ old('device_type')=='Laptop'  ? 'selected':'' }}>Laptop</option>
                <option value="Desktop" {{ old('device_type')=='Desktop' ? 'selected':'' }}>Desktop</option>
                <option value="Mobile"  {{ old('device_type')=='Mobile'  ? 'selected':'' }}>Mobile</option>
                <option value="Tablet"  {{ old('device_type')=='Tablet'  ? 'selected':'' }}>Tablet</option>
                <option value="Other"   {{ old('device_type')=='Other'   ? 'selected':'' }}>Other</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Operating System <span class="text-red-500">*</span></label>
            <select name="operating_system" required
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">Select OS</option>
                <option value="Windows" {{ old('operating_system')=='Windows' ? 'selected':'' }}>Windows</option>
                <option value="macOS"   {{ old('operating_system')=='macOS'   ? 'selected':'' }}>macOS</option>
                <option value="Linux"   {{ old('operating_system')=='Linux'   ? 'selected':'' }}>Linux</option>
                <option value="Android" {{ old('operating_system')=='Android' ? 'selected':'' }}>Android</option>
                <option value="iOS"     {{ old('operating_system')=='iOS'     ? 'selected':'' }}>iOS</option>
                <option value="Other"   {{ old('operating_system')=='Other'   ? 'selected':'' }}>Other</option>
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="text-sm font-medium text-gray-600">MAC Address <span class="text-red-500">*</span></label>
            <input type="text" id="mac_address" name="mac_address"
                value="{{ old('mac_address') }}"
                placeholder="XX:XX:XX:XX:XX:XX or XX-XX-XX-XX-XX-XX"
                maxlength="17"
                class="w-full border border-gray-300 rounded-lg p-2.5 font-mono text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            <p class="text-xs text-gray-400 mt-1">Format: <code>XX:XX:XX:XX:XX:XX</code> or <code>XX-XX-XX-XX-XX-XX</code></p>
        </div>

    </div>

    <!-- Connection Duration -->
    <div class="mt-4">
        <label class="text-sm font-medium text-gray-600">Connection Duration <span class="text-red-500">*</span></label>
        <div class="flex items-center gap-6 mt-2">
            <label class="flex items-center gap-2 cursor-pointer text-sm">
                <input type="radio" name="connection_duration" value="semester"
                    {{ old('connection_duration')=='semester' ? 'checked':'' }}>
                📅 Semester
            </label>
            <label class="flex items-center gap-2 cursor-pointer text-sm">
                <input type="radio" name="connection_duration" value="annual"
                    {{ old('connection_duration')=='annual' ? 'checked':'' }}>
                📆 Annual
            </label>
            <label class="flex items-center gap-2 cursor-pointer text-sm">
                <input type="radio" name="connection_duration" value="permanent"
                    {{ old('connection_duration')=='permanent' ? 'checked':'' }}>
                ♾️ Permanent
            </label>
        </div>
    </div>

    <!-- DISCLAIMER -->
    <div class="mt-6 bg-gray-50 p-4 rounded-xl border border-gray-200 text-sm text-gray-700">
        <p class="font-medium mb-2 text-red-600">Important Declaration:</p>
        <p>The respective requester/applicant will inform the CITC authority immediately to revoke Internet access once the purpose is served or before the requested duration expires.</p>
        <ul class="list-disc ml-5 mt-3 space-y-1 text-gray-600">
            <li>Internet access will not be used for any activity that may pose a security threat.</li>
            <li>I will be solely responsible for any misuse or harmful activity.</li>
            <li>Up-to-date antivirus protection will be ensured on the registered device.</li>
        </ul>
        <p class="mt-3">By submitting, you agree to abide by IIT Indore's IT Usage Policy.</p>
        <label class="flex items-center mt-3 gap-2 cursor-pointer">
            <input type="checkbox" required class="w-4 h-4 accent-blue-600">
            <span class="font-medium">Accept Declaration</span>
        </label>
    </div>

    <!-- SUBMIT -->
    <div class="text-center mt-6">
        <button type="submit"
            class="bg-blue-700 hover:bg-blue-800 text-white px-10 py-2.5 rounded-xl font-semibold text-sm shadow transition-all">
            Submit Request
        </button>
    </div>

    </form>
</div>

<script>
document.getElementById('approver_email').addEventListener('blur', function() {
    let email = this.value;
    if(email !== "") {
        fetch(`/get-approver?email=${email}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('approver_name').value = data.name || '';
            document.getElementById('approver_designation').value = data.designation || '';
            document.getElementById('approver_department').value = data.department || '';
        })
        .catch(error => console.log("Error:", error));
    }
});

document.getElementById('internetForm').addEventListener('submit', function(e) {
    let name = document.getElementById('approver_name').value;
    let designation = document.getElementById('approver_designation').value;
    let department = document.getElementById('approver_department').value;
    if(name === "" || designation === "" || department === "") {
        e.preventDefault();
        alert("Please fill all details of approver");
        return;
    }
    let mac = document.getElementById('mac_address').value;
    let macRegex = /^([0-9A-Fa-f]{2}[:\-]){5}([0-9A-Fa-f]{2})$/;
    if(!macRegex.test(mac)) {
        e.preventDefault();
        alert("MAC Address format is invalid. Use XX:XX:XX:XX:XX:XX or XX-XX-XX-XX-XX-XX");
    }
});
</script>

@endsection
