@extends('layouts.dashboard')

@section('title', 'Web Hosting Request')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">

    <h1 class="text-2xl font-bold text-center text-blue-700 mb-2">Web Hosting Request Form</h1>
    <p class="text-center text-gray-400 text-sm mb-6">Indian Institute of Technology Indore — CITC</p>

    @if(session('success'))
    <div id="success-message"
         class="mb-5 bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 text-sm">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(function () {
            window.location.href = "{{ route('dashboard') }}";
        }, 1000);
    </script>
    @endif

    @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 text-sm">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc ml-5 space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form id="hostingForm" action="{{ route('hosting.store') }}" method="POST">
        @csrf

        {{-- ── SECTION 1: Requester Details ── --}}
        <h2 class="text-base font-semibold text-gray-700 mb-3">Requester Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Pre-filled from Google --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Institute Email ID <span class="text-red-500">*</span></label>
                <input type="email"
                       name="institute_email"
                       value="{{ old('institute_email', auth()->user()->email) }}"
                       readonly
                       class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 text-sm mt-1">
                @error('institute_email')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Name of Owner <span class="text-red-500">*</span></label>
                <input type="text"
                       name="owner_name"
                       value="{{ old('owner_name', auth()->user()->name) }}"
                       readonly
                       class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 text-sm mt-1">
                @error('owner_name')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Department Name <span class="text-red-500">*</span></label>
                <input type="text"
                       name="department_name"
                       value="{{ old('department_name') }}"
                       placeholder="e.g. Computer & IT Centre (CITC)"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                @error('department_name')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Mobile Number <span class="text-red-500">*</span></label>
                <input type="tel"
                       name="mobile_number"
                       value="{{ old('mobile_number') }}"
                       maxlength="10"
                       placeholder="10-digit mobile number"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                @error('mobile_number')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Employee Category <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap items-center gap-6 mt-2 text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="employee_category" value="Faculty"
                            {{ old('employee_category') === 'Faculty' ? 'checked' : '' }}>
                        Faculty
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="employee_category" value="Staff"
                            {{ old('employee_category') === 'Staff' ? 'checked' : '' }}>
                        Staff
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="employee_category" value="Student"
                            {{ old('employee_category') === 'Student' ? 'checked' : '' }}>
                        Student
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="employee_category" value="Research Scholar"
                            {{ old('employee_category') === 'Research Scholar' ? 'checked' : '' }}>
                        Research Scholar
                    </label>
                </div>
                @error('employee_category')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

        </div>

        {{-- ── SECTION 2: Approver Details ── --}}
        <h2 class="text-base font-semibold text-gray-700 mt-7 mb-3">Approver Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-sm font-medium text-gray-600">Approver Email <span class="text-red-500">*</span></label>
                <input type="email"
                       id="approver_email"
                       name="approver_email"
                       value="{{ old('approver_email') }}"
                       placeholder="approver@iiti.ac.in"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <small class="text-gray-400 text-xs mt-1 block">Tab out after typing email to auto-fill details</small>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Approver Name</label>
                <input type="text"
                       id="approver_name"
                       name="approver_name"
                       value="{{ old('approver_name') }}"
                       readonly
                       placeholder="Auto-filled from ERP"
                       class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 text-sm mt-1">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Designation</label>
                <input type="text"
                       id="approver_designation"
                       name="approver_designation"
                       value="{{ old('approver_designation') }}"
                       readonly
                       placeholder="Auto-filled from ERP"
                       class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 text-sm mt-1">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Department</label>
                <input type="text"
                       id="approver_department"
                       name="approver_department"
                       value="{{ old('approver_department') }}"
                       readonly
                       placeholder="Auto-filled from ERP"
                       class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 text-sm mt-1">
            </div>

        </div>

        {{-- ── SECTION 3: Hosting Details ── --}}
        <h2 class="text-base font-semibold text-gray-700 mt-7 mb-3">Hosting Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-sm font-medium text-gray-600">Website Name <span class="text-red-500">*</span></label>
                <input type="text"
                       name="website_name"
                       value="{{ old('website_name') }}"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                @error('website_name')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Suggested Domain Name <span class="text-red-500">*</span></label>
                <input type="text"
                       name="suggested_domain_name"
                       value="{{ old('suggested_domain_name') }}"
                       placeholder="e.g. myproject.iiti.ac.in"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                @error('suggested_domain_name')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Operating System <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-6 mt-2 text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="operating_system" value="Linux"
                            {{ old('operating_system') === 'Linux' ? 'checked' : '' }}>
                        Linux
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="operating_system" value="Windows"
                            {{ old('operating_system') === 'Windows' ? 'checked' : '' }}>
                        Windows
                    </label>
                </div>
                @error('operating_system')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Purpose <span class="text-red-500">*</span></label>
                <textarea name="purpose" rows="4"
                    class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{ old('purpose') }}</textarea>
                @error('purpose')<small class="text-red-500 text-xs">{{ $message }}</small>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-600">Comment (Optional)</label>
                <textarea name="comment" rows="3"
                    class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{ old('comment') }}</textarea>
            </div>

        </div>

        <div class="text-center mt-8">
            <button id="hostingSubmitBtn"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-10 py-2.5 rounded-xl font-semibold text-sm shadow transition-all">
                Submit Request
            </button>
        </div>

    </form>
</div>

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

// ── Validate approver is filled before submit ──
document.getElementById('hostingForm').addEventListener('submit', function (e) {
    const name        = document.getElementById('approver_name').value.trim();
    const designation = document.getElementById('approver_designation').value.trim();
    const department  = document.getElementById('approver_department').value.trim();

    if (!name || !designation || !department) {
        e.preventDefault();
        // alert('Please enter a valid approver email and wait for their details to auto-fill before submitting.');
    }
});
</script>

@endsection