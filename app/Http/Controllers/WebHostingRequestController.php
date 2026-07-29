<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebHostingRequest;

class WebHostingRequestController extends Controller
{
    // Show Form
    public function create()
    {
        return view('hosting.create');
    }

    // Save Data
    public function store(Request $request)
    {
        $validated = $request->validate(
        [
            'institute_email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@iiti\.ac\.in$/'
            ],
            'department_name' => 'required',
            'owner_name' => 'required',
            'mobile_number' => 'required|digits:10',
            'employee_category' => 'required',
            'website_name' => 'required',
            'suggested_domain_name' => 'required',
            'operating_system' => 'required',
            'purpose' => 'required',
            'comment' => 'nullable',
        ],
        [
            'institute_email.required' => 'Institute Email ID is required.',
            'institute_email.email' => 'Please enter a valid email address.',
            'institute_email.regex' => 'Please enter a valid IIT Indore email address (@iiti.ac.in).',

            'mobile_number.required' => 'Mobile Number is required.',
            'mobile_number.digits' => 'Mobile Number must contain exactly 10 digits.',

            'department_name.required' => 'Department Name is required.',
            'owner_name.required' => 'Owner Name is required.',
            'employee_category.required' => 'Please select an Employee Category.',
            'website_name.required' => 'Website Name is required.',
            'suggested_domain_name.required' => 'Suggested Domain Name is required.',
            'operating_system.required' => 'Please select an Operating System.',
            'purpose.required' => 'Purpose is required.',
        ]
    );

    WebHostingRequest::create($validated);

    return redirect()->back()->with('success', 'Request Submitted Successfully!');
    }
}