<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VpnRequest;
use App\Models\Approver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class VpnController extends Controller
{
    //  FORM PAGE
    public function index()
    {
        return view('vpn-form');
    }

    //  FORM SUBMIT
    public function store(Request $req)
    {
        // VALIDATION
        if (
            empty($req->approver_name) ||
            empty($req->approver_designation) ||
            empty($req->approver_department)
        ) {
            return back()->with('error', 'Please fill all details of approver');
        }

        //  SAVE
        VpnRequest::create([
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'contact' => $req->contact,
            'operating_system' => $req->operating_system,
            'start_date' => $req->start_date,
            'end_date' => $req->end_date,
            'purpose' => $req->purpose,
            'resources' => $req->resources,
            'approver_email' => $req->approver_email,
            'approver_name' => $req->approver_name,
            'approver_designation' => $req->approver_designation,
            'approver_department' => $req->approver_department,
        ]);

        //  MAIL
        Mail::raw("Your VPN request has been submitted successfully.", function ($msg) {
            $msg->to(auth()->user()->email)
                ->subject("VPN Request Submitted");
        });

        return redirect('/vpn-success');
    }

    //  APPROVER AUTO FETCH
    public function getApprover(Request $request)
    {
        $response = Http::get(
            'https://erpone.iiti.ac.in/api/method/telephone_directory.api.get_user_details',
            ['email' => $request->email]
        );

        $user = $response->json()['message'] ?? [];

        return response()->json([
            'name' => $user['full_name'] ?? $user['employee_name'] ?? '',
            'designation' => $user['designation'] ?? '',
            'department' => $user['department'] ?? '',
        ]);
    }

    //  APPROVER LOGIN (FIXED)
    public function approverLogin(Request $req)
    {
        $email = strtolower(trim($req->email));

        $response = Http::get(
            'https://erpone.iiti.ac.in/api/method/telephone_directory.api.get_user_details',
            ['email' => $email]
        );

        $user = $response->json()['message'] ?? null;

        if (!$user) {
            return back()->with('error', 'You are not authorized');
        }

        //  SAVE / UPDATE
        $approver = Approver::updateOrCreate(
            ['email' => $email],
            [
                'name' => $user['employee_name'] ?? $user['full_name'],
                'designation' => $user['designation'] ?? '',
                'last_login' => Carbon::now()
            ]
        );

        //  CRITICAL FIX
        session([
            'is_approver' => true,
            'approver_id' => $approver->id,
            'approver_name' => $approver->name
        ]);

        return redirect('/approver-dashboard');
    }

    //  APPROVER DASHBOARD (ONLY ONE)
    public function approverDashboard()
    {
        if (!session('is_approver')) {
            return redirect('/dashboard');
        }

        return view('approver-dashboard');
    }

    //  USER DASHBOARD (OPTIONAL)
    public function dashboard()
    {
        return view('dashboard');
    }

    //  LOGOUT
    public function logout()
    {
        session()->flush();
        return redirect('/approver-login');
    }
}