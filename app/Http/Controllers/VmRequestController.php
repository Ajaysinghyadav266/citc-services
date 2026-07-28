<?php

namespace App\Http\Controllers;

use App\Models\VmRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class VmRequestController extends Controller
{
    /**
     * Show the "New VM Request Application" form.
     */
    public function create(): View
    {
        $user = auth()->user();

        return view('vm-requests', [
            'departmentName' => $user->department_name ?? null,
            'ownerName'      => $user->name ?? null,
            'ownerEmail'     => $user->email ?? null,
        ]);
        // return view('vm-requests');
    }
    /**
     * Validate and persist a new VM request.
     * These rules mirror the client-side checks in resources/js/vm-request.js.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'institute_email'   => ['required', 'email'],
            'department_name'   => ['required', 'string', 'max:150'],
            'owner_name'        => ['required', 'string', 'min:3', 'max:150'],
            'mobile_number'     => ['required', 'regex:/^[6-9][0-9]{9}$/'],
            'employee_category' => ['required', 'in:faculty,staff,research_scholar,student,other'],
            'operating_system'  => ['required', 'string', 'max:100'],
            'vm_expiry_date'    => ['required', 'date', 'after:today'],
            'os_type'           => ['required', 'in:32_bit,64_bit'],
            'purpose_usage'     => ['required', 'string', 'min:10'],
            'cpu_cores'         => ['required', 'integer', 'min:1', 'max:64'],
            'ram_gb'            => ['required', 'integer', 'min:1', 'max:512'],
            'justification'     => ['required', 'string', 'min:10'],
            'hard_disk_gb'      => ['required', 'integer', 'min:1', 'max:10000'],
            'license_type'      => ['required', 'in:open_source,licensed,freeware'],
            'sub_domain'        => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/'],
            'software_list'     => ['required', 'string', 'min:3'],
            'ssl_configuration' => ['required', 'in:yes,no'],
            'i_confirm'         => ['accepted'],
        ]);

        try {
            VmRequest::create($validated);
            return redirect()
                ->route('vm-requests')
                ->with('success', 'VM request submitted successfully.');
        } catch (Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Unable to submit VM request. Please try again.');
        }
    }
}