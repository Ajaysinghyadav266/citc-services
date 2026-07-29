<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternetAccessRequest;
use Illuminate\Support\Facades\Mail;

class InternetAccessController extends Controller
{
    // FORM PAGE
    public function create()
    {
        return view('internet-access.create');
    }

    // FORM SUBMIT
    public function store(Request $request)
    {
        // VALIDATION
        $request->validate([
            'roll_no'             => 'required',
            'phone'               => 'required|digits:10',
            'approver_email'      => 'required|email',
            'approver_name'       => 'required',
            'approver_designation'=> 'required',
            'approver_department' => 'required',
            'device_type'         => 'required',
            'operating_system'    => 'required',
            'mac_address'         => [
                'required',
                'regex:/^([0-9A-Fa-f]{2}[:\-]){5}([0-9A-Fa-f]{2})$/',
            ],
            'connection_duration' => 'required|in:semester,annual,permanent',
        ], [
            'roll_no.required'              => 'Roll No / Employee ID is required.',
            'phone.required'                => 'Phone number is required.',
            'phone.digits'                  => 'Phone number must be exactly 10 digits.',
            'approver_email.required'       => 'Approver Email is required.',
            'approver_email.email'          => 'Please enter a valid approver email.',
            'approver_name.required'        => 'Approver details could not be fetched. Please verify the approver email.',
            'approver_designation.required' => 'Approver designation could not be fetched.',
            'approver_department.required'  => 'Approver department could not be fetched.',
            'device_type.required'          => 'Please select a device type.',
            'operating_system.required'     => 'Please select an operating system.',
            'mac_address.required'          => 'MAC Address is required.',
            'mac_address.regex'             => 'MAC Address format is invalid. Use XX:XX:XX:XX:XX:XX or XX-XX-XX-XX-XX-XX.',
            'connection_duration.required'  => 'Please select a connection duration.',
            'connection_duration.in'        => 'Invalid connection duration selected.',
        ]);

        // SAVE
        InternetAccessRequest::create([
            'name'                 => auth()->user()->name,
            'email'                => auth()->user()->email,
            'roll_no'              => $request->roll_no,
            'phone'                => $request->phone,
            'approver_email'       => $request->approver_email,
            'approver_name'        => $request->approver_name,
            'approver_designation' => $request->approver_designation,
            'approver_department'  => $request->approver_department,
            'device_type'          => $request->device_type,
            'operating_system'     => $request->operating_system,
            'mac_address'          => $request->mac_address,
            'connection_duration'  => $request->connection_duration,
            'status'               => 'pending',
        ]);

        // MAIL — notify applicant
        Mail::raw(
            "Dear " . auth()->user()->name . ",\n\nYour Internet Access request has been submitted successfully and is pending approval.\n\nRegards,\nIIT Indore CITC",
            function ($msg) {
                $msg->to(auth()->user()->email)
                    ->subject('Internet Access Request Submitted — IIT Indore');
            }
        );

        return redirect()->route('internet-access.success');
    }

    // SUCCESS PAGE
    public function success()
    {
        return view('internet-access.success');
    }
}
