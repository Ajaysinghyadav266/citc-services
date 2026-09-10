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
    <div class="mb-5 bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3">
        <p class="font-semibold">Please fix the following:</p>

        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
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

        {{-- ── SECTION 2: Recommender Details ── --}}
        <h2 class="text-base font-semibold text-gray-700 mt-7 mb-3">Recommender Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-sm font-medium text-gray-600">Recommender Email <span class="text-red-500">*</span></label>
                <input type="email"
                       id="approver_email"
                       name="approver_email"
                       value="{{ old('approver_email') }}"
                       placeholder="recommender@iiti.ac.in"
                       class="w-full border border-gray-300 rounded-lg p-2.5 text-sm mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <small class="text-gray-400 text-xs mt-1 block">Tab out after typing email to auto-fill details</small>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-600">Recommender Name</label>
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
           <button type="submit" id="hostingSubmitBtn" class="bg-blue-700 hover:bg-blue-800 text-white px-10 py-2.5 rounded-xl font-semibold text-sm shadow transition-all">
                Submit Request
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('hostingForm');
    const submitBtn = document.getElementById('hostingSubmitBtn');

    const approverEmail = document.getElementById('approver_email');
    const approverName = document.getElementById('approver_name');
    const approverDesignation = document.getElementById('approver_designation');
    const approverDepartment = document.getElementById('approver_department');

    /* =========================================================
       TOAST HELPER
       Uses your existing showToast() function
    ========================================================= */

    function toast(message, type = 'error') {
        if (typeof showToast === 'function') {
            showToast(message, type);
        }
    }


    /* =========================================================
       REMOVE SERVER-SIDE ERROR WHEN USER CORRECTS FIELD
    ========================================================= */

    function removeFieldError(fieldName) {

        // Remove inline Laravel error
        const errorMessages = document.querySelectorAll(
            `[data-field-error="${fieldName}"]`
        );

        errorMessages.forEach(error => error.remove());

        // Remove invalid styling
        const fields = document.querySelectorAll(
            `[name="${fieldName}"]`
        );

        fields.forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.remove('ring-1');
            field.classList.remove('ring-red-400');
        });
    }


    /* =========================================================
       ADD CLIENT-SIDE ERROR
    ========================================================= */

    function showFieldError(fieldName, message) {

        removeFieldError(fieldName);

        const fields = document.querySelectorAll(
            `[name="${fieldName}"]`
        );

        if (!fields.length) return;

        const firstField = fields[0];

        fields.forEach(field => {
            field.classList.add('border-red-500');
        });

        // Find the field's parent block
        let container = firstField.closest('.md\\:col-span-2');

        if (!container) {
            container = firstField.closest('div');
        }

        if (!container) return;

        const error = document.createElement('small');

        error.className = 'text-red-500 text-xs block mt-1';
        error.dataset.fieldError = fieldName;
        error.textContent = message;

        container.appendChild(error);
    }


    /* =========================================================
       LIVE ERROR REMOVAL
    ========================================================= */

    form.querySelectorAll('input, textarea, select').forEach(field => {

        field.addEventListener('input', function () {
            removeFieldError(this.name);
        });

        field.addEventListener('change', function () {
            removeFieldError(this.name);
        });
    });


    /* =========================================================
       RECOMMENDER EMAIL → AUTO FETCH ERP DETAILS
    ========================================================= */

    approverEmail.addEventListener('blur', async function () {

        const email = this.value.trim();

        removeFieldError('approver_email');

        // Clear previous recommender details
        approverName.value = '';
        approverDesignation.value = '';
        approverDepartment.value = '';

        if (!email) {
            return;
        }

        // Basic email/domain validation
        const emailPattern =
            /^[a-zA-Z0-9._%+-]+@iiti\.ac\.in$/;

        if (!emailPattern.test(email)) {

            showFieldError(
                'approver_email',
                'Please enter a valid IIT Indore email address (@iiti.ac.in).'
            );

            return;
        }

        // Loading state
        toast('Fetching recommender details...', 'loading');

        try {

            const response = await fetch(
                `/get-approver?email=${encodeURIComponent(email)}`
            );

            if (!response.ok) {
                throw new Error('Failed to fetch recommender details');
            }

            const data = await response.json();

            approverName.value = data.name || '';
            approverDesignation.value = data.designation || '';
            approverDepartment.value = data.department || '';

            if (
                !approverName.value ||
                !approverDesignation.value ||
                !approverDepartment.value
            ) {

                showFieldError(
                    'approver_email',
                    'Recommender details could not be found. Please enter a valid recommender email.'
                );

                return;
            }

            // Remove any previous errors
            removeFieldError('approver_email');
            removeFieldError('approver_name');
            removeFieldError('approver_designation');
            removeFieldError('approver_department');

        } catch (error) {

            console.error('Recommender lookup failed:', error);

            showFieldError(
                'approver_email',
                'Unable to fetch recommender details. Please try again.'
            );
        }
    });


    /* =========================================================
       VALIDATION RULES
    ========================================================= */

    function validateForm() {

        let isValid = true;
        let firstInvalidField = null;

        function invalid(name, message) {

            showFieldError(name, message);

            if (!firstInvalidField) {
                firstInvalidField =
                    document.querySelector(`[name="${name}"]`);
            }

            isValid = false;
        }


        // Institute email
        const instituteEmail =
            document.querySelector('[name="institute_email"]').value.trim();

        if (!instituteEmail) {

            invalid(
                'institute_email',
                'Institute Email ID is required.'
            );
        }


        // Owner name
        const ownerName =
            document.querySelector('[name="owner_name"]').value.trim();

        if (!ownerName) {

            invalid(
                'owner_name',
                'Owner Name is required.'
            );
        }


        // Department
        const department =
            document.querySelector('[name="department_name"]').value.trim();

        if (!department) {

            invalid(
                'department_name',
                'Department Name is required.'
            );
        }


        // Mobile
        const mobile =
            document.querySelector('[name="mobile_number"]').value.trim();

        if (!mobile) {

            invalid(
                'mobile_number',
                'Mobile Number is required.'
            );

        } else if (!/^\d{10}$/.test(mobile)) {

            invalid(
                'mobile_number',
                'Mobile Number must contain exactly 10 digits.'
            );
        }


        // Employee category
        const employeeCategory =
            document.querySelector(
                'input[name="employee_category"]:checked'
            );

        if (!employeeCategory) {

            invalid(
                'employee_category',
                'Please select an Employee Category.'
            );
        }


        // Recommender email
        const approverEmailValue =
            approverEmail.value.trim();

        if (!approverEmailValue) {

            invalid(
                'approver_email',
                'Recommender Email is required.'
            );

        } else if (
            !/^[a-zA-Z0-9._%+-]+@iiti\.ac\.in$/.test(approverEmailValue)
        ) {

            invalid(
                'approver_email',
                'Please enter a valid IIT Indore email address (@iiti.ac.in).'
            );
        }


        // Recommender details
        if (!approverName.value.trim()) {

            invalid(
                'approver_name',
                'Recommender Name could not be loaded.'
            );
        }

        if (!approverDesignation.value.trim()) {

            invalid(
                'approver_designation',
                'Recommender Designation could not be loaded.'
            );
        }

        if (!approverDepartment.value.trim()) {

            invalid(
                'approver_department',
                'Recommender Department could not be loaded.'
            );
        }


        // Website name
        const websiteName =
            document.querySelector('[name="website_name"]').value.trim();

        if (!websiteName) {

            invalid(
                'website_name',
                'Website Name is required.'
            );
        }


        // Domain
        const domainName =
            document.querySelector(
                '[name="suggested_domain_name"]'
            ).value.trim();

        if (!domainName) {

            invalid(
                'suggested_domain_name',
                'Suggested Domain Name is required.'
            );
        }


        // Operating system
        const operatingSystem =
            document.querySelector(
                'input[name="operating_system"]:checked'
            );

        if (!operatingSystem) {

            invalid(
                'operating_system',
                'Please select an Operating System.'
            );
        }


        // Purpose
        const purpose =
            document.querySelector('[name="purpose"]').value.trim();

        if (!purpose) {

            invalid(
                'purpose',
                'Purpose is required.'
            );
        }


        /* =====================================================
           SCROLL TO FIRST INVALID FIELD
        ===================================================== */

        if (!isValid && firstInvalidField) {

            firstInvalidField.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            firstInvalidField.focus({
                preventScroll: true
            });
        }

        return isValid;
    }


    /* =========================================================
       SUBMIT
    ========================================================= */

    form.addEventListener('submit', function (event) {

        /*
         * IMPORTANT:
         * Client-side validation is only for user experience.
         * Laravel validation remains the final authority.
         */

        if (!validateForm()) {

            event.preventDefault();

            toast(
                'Please fix the highlighted fields before submitting.',
                'error'
            );

            return;
        }


        /* =====================================================
           VALID FORM → ALLOW LARAVEL POST
        ===================================================== */

        submitBtn.disabled = true;

        submitBtn.classList.add(
            'opacity-70',
            'cursor-not-allowed'
        );

        submitBtn.innerHTML = `
            <span class="inline-flex items-center gap-2">
                <svg class="animate-spin h-4 w-4"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4">
                    </circle>

                    <path class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>

                Saving...
            </span>
        `;

        toast(
            'Saving your Web Hosting request...',
            'loading'
        );

        /*
         * DO NOT use event.preventDefault() here.
         *
         * Laravel will receive the POST request normally.
         */
    });

});
</script>
@endsection