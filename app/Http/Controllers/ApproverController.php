<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\VpnRequest;
use App\Models\InternetAccessRequest;
use App\Models\VmRequest;
use App\Models\WebHostingRequest;
use Carbon\Carbon;

class ApproverController extends Controller
{
    // ─── CONSTANTS ───────────────────────────────────────────────
    const DOITA_EMAIL = 'ftest@iiti.ac.in';
    const CITC_DEPT   = 'Computer and Information Technology Center (CITC)';

    // ─── LOGIN PAGE ───────────────────────────────────────────────
    public function login()
    {
        return view('approver-login');
    }

    // ─── DETECT LEVEL (called from AuthController after OAuth) ────
    public static function detectApproverLevel(string $email): int
    {
        // Level 2: fixed dean email
        if (strtolower($email) === self::DOITA_EMAIL) {
            return 2;
        }

        // Call ERP API
        $response = Http::timeout(8)->get(
            'https://erpone.iiti.ac.in/api/method/telephone_directory.api.get_user_details',
            ['email' => $email]
        );
        $userData = $response->json()['message'] ?? null;

        if (!$userData) {
            return 0; // Not in ERP
        }

        $dept = $userData['department'] ?? '';

        // Level 3: CITC department
        if (str_contains($dept, 'Computer and Information Technology Center')) {
            return 3;
        }

        // Level 1: any valid ERP person
        return 1;
    }

    // ─── REDIRECT AFTER GOOGLE LOGIN ─────────────────────────────
    public function handleCallback()
    {
        $user  = Auth::user();
        $email = strtolower($user->email);
        $level = self::detectApproverLevel($email);

        if ($level === 0) {
            Auth::logout();
            return redirect('/approver-login')
                ->with('error', 'Access denied login Please Contact Admin.');
        }

        session([
            'approver_level'      => $level,
            'approver_email'      => $email,
            'approver_name'       => $user->name,
            'approver_login_intent' => null,
        ]);

        return redirect('/approver/dashboard');
    }

    // ─── MIDDLEWARE GATE ──────────────────────────────────────────
    private function gateCheck()
    {
        if (!Auth::check() || !session('approver_level')) {
            abort(403, 'Approver access required.');
        }
    }

    // ─── HELPER: ALL REQUESTS (UNIFIED) ──────────────────────────
    private function allRequests(?string $statusFilter = null): array
    {
        $level = session('approver_level');
        $email = session('approver_email');

        $models = [
            'VPN'             => VpnRequest::class,
            'Internet Access' => InternetAccessRequest::class,
            'VM Request'      => VmRequest::class,
            'Web Hosting'     => WebHostingRequest::class,
        ];

        $requests = [];

        foreach ($models as $label => $model) {
            $query = $model::query();

            // Level 1 sees only requests where approver_email matches their email
            if ($level === 1) {
                $query->where('approver_email', $email);
            }

            if ($statusFilter) {
                $query->where('approval_status', $statusFilter);
            } elseif ($level === 1) {
                $query->where('approval_status', 'pending');
            } elseif ($level === 2) {
                $query->where('approval_status', 'approved_by_1');
            } elseif ($level === 3) {
                $query->where('approval_status', 'approved_by_2');
            }

            foreach ($query->latest()->get() as $item) {
                $item->_type  = $label;
                $item->_model = class_basename($model);
                $requests[] = $item;
            }
        }

        // Sort by created_at descending
        usort($requests, fn($a, $b) => $b->created_at <=> $a->created_at);

        return $requests;
    }

    // ─── DASHBOARD ────────────────────────────────────────────────
    public function dashboard()
    {
        $this->gateCheck();
        $level = session('approver_level');
        $email = session('approver_email');

        // Initialise all stats to 0 (not all are used by every level)
        $pending = $approved = $rejected = $completed = 0;

        // Stats
        $models = [VpnRequest::class, InternetAccessRequest::class, VmRequest::class, WebHostingRequest::class];

        if ($level === 1) {
            $pending  = collect($models)->sum(fn($m) => $m::where('approver_email', $email)->where('approval_status', 'pending')->count());
            $approved = collect($models)->sum(fn($m) => $m::where('approver_email', $email)->where('approval_status', '!=', 'pending')->where('approval_status', '!=', 'rejected')->count());
            $rejected = collect($models)->sum(fn($m) => $m::where('approver_email', $email)->where('approval_status', 'rejected')->where('rejected_by_level', 1)->count());
        } elseif ($level === 2) {
            $pending  = collect($models)->sum(fn($m) => $m::where('approval_status', 'approved_by_1')->count());
            $approved = collect($models)->sum(fn($m) => $m::where('approval_status', 'approved_by_2')->orWhere('approval_status', 'completed')->count());
            $rejected = collect($models)->sum(fn($m) => $m::where('approval_status', 'rejected')->where('rejected_by_level', 2)->count());
        } else {
            $pending   = collect($models)->sum(fn($m) => $m::where('approval_status', 'approved_by_2')->count());
            $completed = collect($models)->sum(fn($m) => $m::where('approval_status', 'completed')->count());
        }

        // Recent
        $recent = $this->allRequests();
        $recent = array_slice($recent, 0, 5);

        return view('approver.dashboard', compact('level', 'pending', 'approved', 'rejected', 'completed', 'recent'));
    }

    // ─── PENDING REQUESTS ─────────────────────────────────────────
    public function pendingRequests()
    {
        $this->gateCheck();
        $level    = session('approver_level');
        $requests = $this->allRequests(); // already filtered to level's pending state

        return view('approver.pending', compact('level', 'requests'));
    }

    // ─── APPROVED REQUESTS ────────────────────────────────────────
    public function approvedRequests()
    {
        $this->gateCheck();
        $level = session('approver_level');
        $email = session('approver_email');
        $models = [VpnRequest::class, InternetAccessRequest::class, VmRequest::class, WebHostingRequest::class];
        $typeMap = [
            VpnRequest::class            => 'VPN',
            InternetAccessRequest::class => 'Internet Access',
            VmRequest::class             => 'VM Request',
            WebHostingRequest::class     => 'Web Hosting',
        ];

        $requests = [];
        foreach ($models as $model) {
            $query = $model::query();
            if ($level === 1) {
                $query->where('approver_email', $email)->where('approval_status', '!=', 'pending');
            } elseif ($level === 2) {
                $query->whereIn('approval_status', ['approved_by_2', 'completed']);
            }
            foreach ($query->latest()->get() as $item) {
                $item->_type  = $typeMap[$model];
                $item->_model = class_basename($model);
                $requests[] = $item;
            }
        }
        usort($requests, fn($a, $b) => $b->created_at <=> $a->created_at);

        return view('approver.approved', compact('level', 'requests'));
    }

    // ─── REJECTED REQUESTS ────────────────────────────────────────
    public function rejectedRequests()
    {
        $this->gateCheck();
        $level = session('approver_level');
        $email = session('approver_email');
        $models = [VpnRequest::class, InternetAccessRequest::class, VmRequest::class, WebHostingRequest::class];
        $typeMap = [
            VpnRequest::class            => 'VPN',
            InternetAccessRequest::class => 'Internet Access',
            VmRequest::class             => 'VM Request',
            WebHostingRequest::class     => 'Web Hosting',
        ];

        $requests = [];
        foreach ($models as $model) {
            $query = $model::where('approval_status', 'rejected');
            if ($level === 1) {
                $query->where('approver_email', $email)->where('rejected_by_level', 1);
            } elseif ($level === 2) {
                $query->where('rejected_by_level', 2);
            }
            foreach ($query->latest()->get() as $item) {
                $item->_type  = $typeMap[$model];
                $item->_model = class_basename($model);
                $requests[] = $item;
            }
        }
        usort($requests, fn($a, $b) => $b->created_at <=> $a->created_at);

        return view('approver.rejected', compact('level', 'requests'));
    }

    // ─── CITC: PENDING ────────────────────────────────────────────
    public function citcPending()
    {
        $this->gateCheck();
        if (session('approver_level') !== 3) abort(403);

        $models = [VpnRequest::class, InternetAccessRequest::class, VmRequest::class, WebHostingRequest::class];
        $typeMap = [
            VpnRequest::class            => 'VPN',
            InternetAccessRequest::class => 'Internet Access',
            VmRequest::class             => 'VM Request',
            WebHostingRequest::class     => 'Web Hosting',
        ];

        $requests = [];
        foreach ($models as $model) {
            foreach ($model::where('approval_status', 'approved_by_2')->latest()->get() as $item) {
                $item->_type  = $typeMap[$model];
                $item->_model = class_basename($model);
                $requests[] = $item;
            }
        }
        usort($requests, fn($a, $b) => $b->created_at <=> $a->created_at);

        return view('approver.citc-pending', compact('requests'));
    }

    // ─── CITC: COMPLETED ─────────────────────────────────────────
    public function citcCompleted()
    {
        $this->gateCheck();
        if (session('approver_level') !== 3) abort(403);

        $models = [VpnRequest::class, InternetAccessRequest::class, VmRequest::class, WebHostingRequest::class];
        $typeMap = [
            VpnRequest::class            => 'VPN',
            InternetAccessRequest::class => 'Internet Access',
            VmRequest::class             => 'VM Request',
            WebHostingRequest::class     => 'Web Hosting',
        ];

        $requests = [];
        foreach ($models as $model) {
            foreach ($model::where('approval_status', 'completed')->latest()->get() as $item) {
                $item->_type  = $typeMap[$model];
                $item->_model = class_basename($model);
                $requests[] = $item;
            }
        }
        usort($requests, fn($a, $b) => $b->created_at <=> $a->created_at);

        return view('approver.citc-completed', compact('requests'));
    }

    // ─── APPROVE ACTION ───────────────────────────────────────────
    public function approve(Request $request, string $type, int $id)
    {
        $this->gateCheck();
        $level = session('approver_level');
        $name  = session('approver_name');
        $email = session('approver_email');

        $model = $this->resolveModel($type);
        if (!$model) return back()->with('error', 'Unknown request type.');

        $rec = $model::findOrFail($id);

        if ($level === 1 && $rec->approval_status === 'pending') {
            $rec->update([
                'approval_status'  => 'approved_by_1',
                'approver1_email'  => $email,
                'approver1_name'   => $name,
                'approved_by_1_at' => Carbon::now(),
            ]);
        } elseif ($level === 2 && $rec->approval_status === 'approved_by_1') {
            $rec->update([
                'approval_status'  => 'approved_by_2',
                'approver2_email'  => $email,
                'approver2_name'   => $name,
                'approved_by_2_at' => Carbon::now(),
            ]);
        } elseif ($level === 3 && $rec->approval_status === 'approved_by_2') {
            $rec->update([
                'approval_status'   => 'completed',
                'citc_completed_by' => $name,
                'citc_completed_at' => Carbon::now(),
            ]);
        } else {
            return back()->with('error', 'This request cannot be approved at your level right now.');
        }

        return back()->with('success', 'Request approved successfully.');
    }

    // ─── REJECT ACTION ────────────────────────────────────────────
    public function reject(Request $request, string $type, int $id)
    {
        $this->gateCheck();
        $request->validate(['reason' => 'required|string|max:1000']);

        $level = session('approver_level');
        $name  = session('approver_name');
        $email = session('approver_email');

        $model = $this->resolveModel($type);
        if (!$model) return back()->with('error', 'Unknown request type.');

        $rec = $model::findOrFail($id);

        $rec->update([
            'approval_status'  => 'rejected',
            'rejected_by'      => $email,
            'rejected_by_level'=> $level,
            'rejection_reason' => $request->reason,
            'rejected_at'      => Carbon::now(),
        ]);

        return back()->with('success', 'Request rejected.');
    }

    // ─── HELPER: RESOLVE MODEL ────────────────────────────────────
    private function resolveModel(string $type): ?string
    {
        return match ($type) {
            'vpn'      => VpnRequest::class,
            'internet' => InternetAccessRequest::class,
            'vm'       => VmRequest::class,
            'hosting'  => WebHostingRequest::class,
            default    => null,
        };
    }
}
