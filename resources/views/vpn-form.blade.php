@extends('layouts.dashboard')

@section('title', 'VPN Request')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold text-center text-blue-700 mb-2">
        REQUEST FOR VPN ACCESS
    </h1>
    <p class="text-center text-gray-400 text-sm mb-6">Indian Institute of Technology Indore — CITC</p>

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form id="vpnForm" action="/vpn-submit" method="POST">
    @csrf

    <!-- USER DETAILS -->
    <h2 class="text-base font-semibold text-gray-700 mb-3 mt-2">User Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="text-sm font-medium text-gray-600">Name</label>
            <input type="text" value="{{ auth()->user()->name }}" readonly
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 text-sm mt-1">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Email</label>
            <input type="email" value="{{ auth()->user()->email }}" readonly
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 text-sm mt-1">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Contact Number <span class="text-red-500">*</span></label>
            <input type="text" name="contact" required
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Operating System</label>
            <select name="operating_system"
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option>Linux</option>
                <option>Ubuntu</option>
                <option>Windows</option>
                <option>Mac</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Start Date <span class="text-red-500">*</span></label>
            <input type="date" name="start_date" required
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">End Date <span class="text-red-500">*</span></label>
            <input type="date" name="end_date" required
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
    </div>

    <!-- VPN DETAILS -->
    <h2 class="text-base font-semibold text-gray-700 mt-6 mb-3">VPN Details</h2>

    <div class="mt-1">
        <label class="text-sm font-medium text-gray-600">Purpose of Activity <span class="text-red-500">*</span></label>
        <textarea name="purpose" rows="3" required
            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
    </div>

    <div class="mt-3">
        <label class="text-sm font-medium text-gray-600">Servers / Resources <span class="text-red-500">*</span></label>
        <textarea name="resources" rows="2" required
            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
    </div>

    <!-- APPROVER DETAILS -->
    <h2 class="text-base font-semibold text-gray-700 mt-6 mb-3">Approver Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="text-sm font-medium text-gray-600">Approver Email <span class="text-red-500">*</span></label>
            <input type="email" id="approver_email" name="approver_email"
                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Approver Name</label>
            <input type="text" id="approver_name" name="approver_name"
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-sm mt-1" readonly>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Designation</label>
            <input type="text" id="approver_designation" name="approver_designation"
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-sm mt-1" readonly>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Department</label>
            <input type="text" id="approver_department" name="approver_department"
                class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-sm mt-1" readonly>
        </div>

    </div>

    <!-- DISCLAIMER -->
    <div class="mt-6 bg-gray-50 p-4 rounded-xl border border-gray-200 text-sm text-gray-700">
        <p class="font-medium mb-2 text-red-600">Important Declaration:</p>
        <p>The respective requester/applicant/PI will inform the ISTF authority immediately to revoke the Internet/VPN access once his/her project ends or the purpose is served before the requested duration.</p>
        <ul class="list-disc ml-5 mt-3 space-y-1 text-gray-600">
            <li>The Internet/VPN access will not be used for any activity that may pose a security threat.</li>
            <li>I will be solely responsible for any misuse or harmful activity.</li>
            <li>Up-to-date antivirus protection will be ensured on devices.</li>
        </ul>
        <p class="mt-3">I have read and understood the above and agree to abide by the rules and regulations.</p>
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

document.getElementById('vpnForm').addEventListener('submit', function(e) {
    let name = document.getElementById('approver_name').value;
    let designation = document.getElementById('approver_designation').value;
    let department = document.getElementById('approver_department').value;
    if(name === "" || designation === "" || department === "") {
        e.preventDefault();
        alert("Please fill all details of approver");
    }
});
</script>

@endsection