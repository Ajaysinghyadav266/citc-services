<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VpnRequest;
use App\Models\InternetAccessRequest;
use App\Models\VmRequest;
use App\Models\WebHostingRequest;

class MyRequestsController extends Controller
{
    /**
     * Display all service requests submitted by the logged in user with timeline info.
     */
    public function index()
    {
        $user  = Auth::user();
        $email = strtolower(trim($user->email));
        $name  = trim($user->name);

        // 1. Fetch VPN Requests
        $vpn = VpnRequest::where(function($q) use ($email, $name) {
            $q->where('email', $email);
            if ($name) $q->orWhere('name', $name);
        })->latest()->get();

        // 2. Fetch Internet Access Requests
        $internet = InternetAccessRequest::where(function($q) use ($email, $name) {
            $q->where('email', $email);
            if ($name) $q->orWhere('name', $name);
        })->latest()->get();

        // 3. Fetch VM Requests
        $vm = VmRequest::where(function($q) use ($email, $name, $user) {
            $q->where('institute_email', $email)
              ->orWhere('requested_by', $user->id);
            if ($name) $q->orWhere('owner_name', $name);
        })->latest()->get();

        // 4. Fetch Web Hosting Requests
        $hosting = WebHostingRequest::where(function($q) use ($email, $name) {
            $q->where('institute_email', $email);
            if ($name) $q->orWhere('owner_name', $name);
        })->latest()->get();

        $requests = [];

        foreach ($vpn as $r) {
            $r->_type        = 'VPN Access';
            $r->_type_slug   = 'vpn';
            $r->_badge_class = 'bg-indigo-100 text-indigo-700 border border-indigo-200';
            $r->_icon_bg    = 'bg-indigo-50 text-indigo-600';
            $r->_summary     = 'Purpose: ' . ($r->purpose ? substr($r->purpose, 0, 60) . '...' : 'VPN connectivity');
            $r->_details_kv  = [
                'Operating System' => $r->operating_system ?? 'N/A',
                'Contact'          => $r->contact ?? 'N/A',
                'Start Date'       => $r->start_date ? date('d M Y', strtotime($r->start_date)) : 'N/A',
                'End Date'         => $r->end_date ? date('d M Y', strtotime($r->end_date)) : 'N/A',
                'Purpose'          => $r->purpose ?? 'N/A',
                'Resources Needed' => $r->resources ?? 'N/A',
            ];
            $requests[] = $r;
        }

        foreach ($internet as $r) {
            $r->_type        = 'Internet Access';
            $r->_type_slug   = 'internet';
            $r->_badge_class = 'bg-cyan-100 text-cyan-700 border border-cyan-200';
            $r->_icon_bg    = 'bg-cyan-50 text-cyan-600';
            $r->_summary     = 'Device: ' . ($r->device_type ?? 'Device') . ' (' . ($r->mac_address ?? 'MAC N/A') . ')';
            $r->_details_kv  = [
                'Roll / Emp ID'       => $r->roll_no ?? 'N/A',
                'Phone'               => $r->phone ?? 'N/A',
                'Device Type'         => $r->device_type ?? 'N/A',
                'Operating System'    => $r->operating_system ?? 'N/A',
                'MAC Address'         => $r->mac_address ?? 'N/A',
                'Connection Duration' => ucfirst($r->connection_duration ?? 'N/A'),
            ];
            $requests[] = $r;
        }

        foreach ($vm as $r) {
            $r->_type        = 'Virtual Machine';
            $r->_type_slug   = 'vm';
            $r->_badge_class = 'bg-green-100 text-green-700 border border-green-200';
            $r->_icon_bg    = 'bg-green-50 text-green-600';
            $r->_summary     = 'OS: ' . ($r->operating_system ?? 'Linux') . ' • ' . ($r->cpu_cores ?? 2) . ' Cores, ' . ($r->ram_gb ?? 4) . 'GB RAM';
            $r->_details_kv  = [
                'Department'        => $r->department_name ?? 'N/A',
                'Mobile'            => $r->mobile_number ?? 'N/A',
                'Employee Category' => ucfirst($r->employee_category ?? 'N/A'),
                'Operating System'  => ($r->operating_system ?? 'N/A') . ' (' . ($r->os_type ?? '64_bit') . ')',
                'Resources'         => ($r->cpu_cores ?? 'N/A') . ' vCPUs, ' . ($r->ram_gb ?? 'N/A') . 'GB RAM, ' . ($r->hard_disk_gb ?? 'N/A') . 'GB Disk',
                'Expiry Date'       => $r->vm_expiry_date ? date('d M Y', strtotime($r->vm_expiry_date)) : 'N/A',
                'License Type'      => ucfirst($r->license_type ?? 'N/A'),
                'Sub Domain'        => $r->sub_domain ?? 'N/A',
                'Software List'     => $r->software_list ?? 'N/A',
                'SSL Required'      => ucfirst($r->ssl_configuration ?? 'N/A'),
                'Purpose / Usage'   => $r->purpose_usage ?? 'N/A',
                'Justification'     => $r->justification ?? 'N/A',
            ];
            $requests[] = $r;
        }

        foreach ($hosting as $r) {
            $r->_type        = 'Web Hosting';
            $r->_type_slug   = 'hosting';
            $r->_badge_class = 'bg-orange-100 text-orange-700 border border-orange-200';
            $r->_icon_bg    = 'bg-orange-50 text-orange-600';
            $r->_summary     = 'Domain: ' . ($r->suggested_domain_name ?? $r->website_name ?? 'Website');
            $r->_details_kv  = [
                'Website Name'          => $r->website_name ?? 'N/A',
                'Suggested Domain'      => $r->suggested_domain_name ?? 'N/A',
                'Department'            => $r->department_name ?? 'N/A',
                'Mobile'                => $r->mobile_number ?? 'N/A',
                'Employee Category'     => ucfirst($r->employee_category ?? 'N/A'),
                'Operating System'      => $r->operating_system ?? 'N/A',
                'Purpose'               => $r->purpose ?? 'N/A',
                'Comment'               => $r->comment ?? 'N/A',
            ];
            $requests[] = $r;
        }

        // Sort all requests combined by created_at descending
        usort($requests, fn($a, $b) => $b->created_at <=> $a->created_at);

        return view('my-requests', compact('requests'));
    }
}
