<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VpnRequest;
use Illuminate\Support\Facades\Mail;

class VpnController extends Controller
{
    public function index()
    {
        return view('vpn-form');
    }

    public function store(Request $req)
    {
        VpnRequest::create([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'contact' => $req->contact,
            'operating_system' => $req->operating_system,
            'start_date' => $req->start_date,
            'end_date' => $req->end_date,
            'reason' => $req->purpose,
            'resources' => $req->resources,
            'faculty_name' => $req->faculty_name,
            'faculty_email' => $req->faculty_email,
            'department' => $req->department,
        ]);

        Mail::raw("Your VPN request has been submitted successfully.", function($msg){
            $msg->to(auth()->user()->email)
                ->subject("VPN Request Submitted");
        });

        return redirect('/vpn-success');
    }
}
