@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white text-center py-3">
            <h2>Web Hosting Request Form</h2>
            <p class="mb-0">Indian Institute of Technology Indore</p>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('hosting.store') }}" method="POST">

                @csrf

                <!-- ================= Requester Details ================= -->

                <div class="bg-light p-3 rounded mb-4">
                    <h4 class="text-primary">Requester Details</h4>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Institute Email ID</label>

                            <input
                                type="email"
                                class="form-control"
                                name="institute_email"
                                value="{{ old('institute_email') }}"
                            >

                            @error('institute_email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Department Name</label>

                            <input
                                type="text"
                                class="form-control"
                                name="department_name"
                                value="{{ old('department_name') }}"
                            >

                            @error('department_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Name of Owner</label>

                            <input
                                type="text"
                                class="form-control"
                                name="owner_name"
                                value="{{ old('owner_name') }}"
                            >

                            @error('owner_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Mobile Number</label>

                            <input
                                type="text"
                                class="form-control"
                                name="mobile_number"
                                value="{{ old('mobile_number') }}"
                            >

                            @error('mobile_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Employee Category</label>

                        <div>

                            <input type="radio" name="employee_category" value="Faculty"> Faculty

                            &nbsp;&nbsp;

                            <input type="radio" name="employee_category" value="Staff"> Staff

                            &nbsp;&nbsp;

                            <input type="radio" name="employee_category" value="Student"> Student

                            &nbsp;&nbsp;

                            <input type="radio" name="employee_category" value="Research Scholar"> Research Scholar

                        </div>

                        @error('employee_category')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>

                <!-- ================= Hosting Details ================= -->

                <div class="bg-light p-3 rounded">

                    <h4 class="text-primary">Hosting Details</h4>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Website Name</label>

                            <input
                                type="text"
                                class="form-control"
                                name="website_name"
                                value="{{ old('website_name') }}"
                            >

                            @error('website_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">Suggested Domain Name</label>

                            <input
                                type="text"
                                class="form-control"
                                name="suggested_domain_name"
                                value="{{ old('suggested_domain_name') }}"
                            >

                            @error('suggested_domain_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Operating System</label>

                        <div>

                            <input type="radio" name="operating_system" value="Linux"> Linux

                            &nbsp;&nbsp;

                            <input type="radio" name="operating_system" value="Windows"> Windows

                        </div>

                        @error('operating_system')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Purpose</label>

                        <textarea
                            class="form-control"
                            rows="4"
                            name="purpose"
                        >{{ old('purpose') }}</textarea>

                        @error('purpose')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="form-label">Comment (Optional)</label>

                        <textarea
                            class="form-control"
                            rows="3"
                            name="comment"
                        >{{ old('comment') }}</textarea>

                    </div>

                </div>

                <div class="text-center mt-4">

                    <button class="btn btn-primary btn-lg px-5">
                        Submit Request
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection