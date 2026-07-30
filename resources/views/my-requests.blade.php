@extends('layouts.dashboard')

@section('title', 'My Requests')

@section('head')
<style>
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(8px);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.open {
        display: flex;
    }
    .timeline-line {
        position: absolute;
        top: 24px;
        bottom: 24px;
        left: 19px;
        width: 2px;
        background: #e2e8f0;
        z-index: 0;
    }
    @keyframes pulseRing {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    .pulse-amber { animation: pulseRing 2s infinite ease-in-out; }
</style>
@endsection

@section('content')

@php
    $totalCount     = count($requests);
    $pendingCount   = collect($requests)->filter(fn($r) => in_array($r->approval_status ?? 'pending', ['pending', 'approved_by_1', 'approved_by_2']))->count();
    $completedCount = collect($requests)->filter(fn($r) => ($r->approval_status ?? '') === 'completed')->count();
    $rejectedCount  = collect($requests)->filter(fn($r) => ($r->approval_status ?? '') === 'rejected')->count();
@endphp

<div class="pt-2">

    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Requests &amp; Status</h1>
            <p class="text-gray-500 text-sm mt-1">Track real-time approval progress and view timeline for all your submitted applications.</p>
        </div>
        <a href="/dashboard" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-4 py-2 rounded-xl hover:bg-blue-100 transition-colors self-start md:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </a>
    </div>

    <!-- Interactive Filter Stat Strip -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <button type="button"
                id="filter-btn-all"
                onclick="filterRequests('all')"
                class="stat-filter-card active bg-white border border-blue-500 ring-2 ring-blue-500/20 rounded-2xl p-4 shadow-sm flex items-center gap-3 text-left transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm shrink-0">
                {{ $totalCount }}
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</p>
                <p class="text-sm font-bold text-gray-800">Submitted</p>
            </div>
        </button>

        <button type="button"
                id="filter-btn-pending"
                onclick="filterRequests('pending')"
                class="stat-filter-card bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center gap-3 text-left transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm shrink-0">
                {{ $pendingCount }}
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">In Progress</p>
                <p class="text-sm font-bold text-amber-600">Pending</p>
            </div>
        </button>

        <button type="button"
                id="filter-btn-completed"
                onclick="filterRequests('completed')"
                class="stat-filter-card bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center gap-3 text-left transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center font-bold text-sm shrink-0">
                {{ $completedCount }}
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Active</p>
                <p class="text-sm font-bold text-green-600">Completed</p>
            </div>
        </button>

        <button type="button"
                id="filter-btn-rejected"
                onclick="filterRequests('rejected')"
                class="stat-filter-card bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex items-center gap-3 text-left transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-sm shrink-0">
                {{ $rejectedCount }}
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Declined</p>
                <p class="text-sm font-bold text-red-600">Rejected</p>
            </div>
        </button>
    </div>

    <!-- Empty state for zero total requests -->
    @if(count($requests) === 0)
    <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">No Submitted Requests Yet</h3>
        <p class="text-gray-500 text-sm max-w-sm mx-auto mb-6">You haven't submitted any service requests. Choose a service below to get started.</p>
        <a href="/dashboard" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition-colors">
            Explore Services &rarr;
        </a>
    </div>
    @else

    <!-- Requests List -->
    <div class="space-y-4" id="requestsContainer">
        @foreach($requests as $idx => $r)
        @php
            $status = $r->approval_status ?? 'pending';

            $group = match(true) {
                in_array($status, ['pending', 'approved_by_1', 'approved_by_2']) => 'pending',
                $status === 'completed' => 'completed',
                $status === 'rejected'  => 'rejected',
                default => 'pending',
            };

            $statusBadge = match($status) {
                'pending'       => ['text' => 'Pending Level 1 Approval', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
                'approved_by_1' => ['text' => 'Level 1 Approved (Awaiting Dean)', 'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500'],
                'approved_by_2' => ['text' => 'Level 2 Approved (Awaiting CITC)', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'dot' => 'bg-indigo-500'],
                'completed'     => ['text' => 'Completed & Service Active', 'class' => 'bg-green-50 text-green-700 border-green-200', 'dot' => 'bg-green-500'],
                'rejected'      => ['text' => 'Rejected', 'class' => 'bg-red-50 text-red-700 border-red-200', 'dot' => 'bg-red-500'],
                default         => ['text' => 'Pending', 'class' => 'bg-gray-50 text-gray-700 border-gray-200', 'dot' => 'bg-gray-400'],
            };
        @endphp

        <div class="request-card-item bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col md:flex-row md:items-center justify-between gap-4"
             data-status-group="{{ $group }}">

            <div class="flex items-start gap-4 min-w-0">
                <div class="w-12 h-12 rounded-2xl {{ $r->_icon_bg }} flex items-center justify-center shrink-0">
                    @if($r->_type_slug === 'vpn')
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h.01M15 12h.01M17 16H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2z"/>
                    </svg>
                    @elseif($r->_type_slug === 'internet')
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                    @elseif($r->_type_slug === 'vm')
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                    </svg>
                    @else
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                    </svg>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $r->_badge_class }}">
                            {{ $r->_type }}
                        </span>
                        <span class="text-xs text-gray-400">&bull; Submitted {{ $r->created_at?->format('d M Y, h:i A') }}</span>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 truncate">
                        {{ $r->name ?? $r->owner_name ?? $r->_type }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $r->_summary }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0 self-end md:self-center">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border {{ $statusBadge['class'] }}">
                    <span class="w-2 h-2 rounded-full {{ $statusBadge['dot'] }}"></span>
                    {{ $statusBadge['text'] }}
                </span>

                <button type="button"
                        onclick="openTimelineModal({{ json_encode($r) }})"
                        class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3.5 py-2 rounded-xl transition-colors">
                    View Timeline
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

        </div>
        @endforeach

        <!-- Empty state when filter yields 0 results -->
        <div id="filterEmptyState" class="hidden bg-white border border-gray-200 rounded-2xl p-8 text-center shadow-sm">
            <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-700">No <span id="filterEmptyLabel"></span> requests found</p>
            <p class="text-xs text-gray-400 mt-1">Try selecting another filter above to view your requests.</p>
        </div>

    </div>
    @endif

</div>

<!-- ====== TIMELINE MODAL ====== -->
<div id="timelineModal" class="modal-overlay" onclick="if(event.target===this)closeTimelineModal()">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden max-h-[90vh] flex flex-col my-6">

        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-slate-900 text-white shrink-0">
            <div class="flex items-center gap-3">
                <span id="modalTypeBadge" class="text-xs font-semibold px-3 py-1 rounded-full bg-white/10 text-white border border-white/20">
                    Service
                </span>
                <div>
                    <h3 id="modalTitle" class="font-bold text-base text-white leading-tight">Request Details</h3>
                    <p id="modalSub" class="text-xs text-slate-400 mt-0.5">Submitted date</p>
                </div>
            </div>
            <button onclick="closeTimelineModal()" class="text-slate-400 hover:text-white transition-colors p-1">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Content Scrollable -->
        <div class="p-6 overflow-y-auto space-y-6">

            <!-- Request Details Grid -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Application Details</h4>
                <div id="modalDetailsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-gray-50 border border-gray-100 rounded-2xl p-4 text-xs">
                    <!-- Dynamic details populated by JS -->
                </div>
            </div>

            <!-- Approval Progress Timeline -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Approval Workflow Timeline</h4>

                <div class="relative pl-3 space-y-6">
                    <div class="timeline-line"></div>

                    <!-- STEP 1: Submitted -->
                    <div class="relative flex items-start gap-4 z-10">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 shadow-md">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="pt-1 min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h5 class="text-sm font-bold text-gray-900">1. Application Submitted</h5>
                                <span id="step1Date" class="text-xs text-gray-400 font-medium">Date</span>
                            </div>
                            <p id="step1Desc" class="text-xs text-gray-500 mt-0.5">Submitted successfully by applicant.</p>
                        </div>
                    </div>

                    <!-- STEP 2: Level 1 Approver -->
                    <div class="relative flex items-start gap-4 z-10" id="step2Container">
                        <div id="step2Icon" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center shrink-0 shadow-md">
                            2
                        </div>
                        <div class="pt-1 min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h5 class="text-sm font-bold text-gray-900">2. Faculty / Staff Approval (Level 1)</h5>
                                <span id="step2Badge" class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600">Pending</span>
                            </div>
                            <p id="step2Approver" class="text-xs text-gray-700 font-medium mt-1">Approver: —</p>
                            <p id="step2Desc" class="text-xs text-gray-500 mt-0.5">Awaiting review by faculty/staff.</p>
                            <div id="step2RejectionBox" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800">
                                <strong>Rejection Reason:</strong> <span id="step2ReasonText"></span>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Level 2 Approver (Dean IT) -->
                    <div class="relative flex items-start gap-4 z-10" id="step3Container">
                        <div id="step3Icon" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center shrink-0 shadow-md">
                            3
                        </div>
                        <div class="pt-1 min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h5 class="text-sm font-bold text-gray-900">3. Dean of IT Infrastructure (Level 2)</h5>
                                <span id="step3Badge" class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600">Waiting</span>
                            </div>
                            <p id="step3Approver" class="text-xs text-gray-700 font-medium mt-1">Approver: Dean of IT Infrastructure (doita@iiti.ac.in)</p>
                            <p id="step3Desc" class="text-xs text-gray-500 mt-0.5">Under review by Dean IT.</p>
                            <div id="step3RejectionBox" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800">
                                <strong>Rejection Reason:</strong> <span id="step3ReasonText"></span>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: Level 3 CITC Execution -->
                    <div class="relative flex items-start gap-4 z-10" id="step4Container">
                        <div id="step4Icon" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center shrink-0 shadow-md">
                            4
                        </div>
                        <div class="pt-1 min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h5 class="text-sm font-bold text-gray-900">4. CITC Team Execution (Level 3)</h5>
                                <span id="step4Badge" class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600">Waiting</span>
                            </div>
                            <p id="step4Approver" class="text-xs text-gray-700 font-medium mt-1">Department: Computer &amp; IT Centre (CITC)</p>
                            <p id="step4Desc" class="text-xs text-gray-500 mt-0.5">Final technical setup &amp; service activation.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end shrink-0">
            <button type="button" onclick="closeTimelineModal()" class="px-5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 transition-colors shadow-sm">
                Close
            </button>
        </div>

    </div>
</div>

<script>
// Filter Function
function filterRequests(group) {
    document.querySelectorAll('.stat-filter-card').forEach(card => {
        card.classList.remove(
            'border-blue-500', 'ring-2', 'ring-blue-500/20',
            'border-amber-500', 'ring-amber-500/20',
            'border-green-500', 'ring-green-500/20',
            'border-red-500', 'ring-red-500/20'
        );
        card.classList.add('border-gray-200');
    });

    const activeBtn = document.getElementById('filter-btn-' + group);
    if (activeBtn) {
        activeBtn.classList.remove('border-gray-200');
        if (group === 'pending') {
            activeBtn.classList.add('border-amber-500', 'ring-2', 'ring-amber-500/20');
        } else if (group === 'completed') {
            activeBtn.classList.add('border-green-500', 'ring-2', 'ring-green-500/20');
        } else if (group === 'rejected') {
            activeBtn.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
        } else {
            activeBtn.classList.add('border-blue-500', 'ring-2', 'ring-blue-500/20');
        }
    }

    const items = document.querySelectorAll('.request-card-item');
    let visibleCount = 0;

    items.forEach(item => {
        const itemGroup = item.getAttribute('data-status-group');
        if (group === 'all' || itemGroup === group) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    const emptyMsg = document.getElementById('filterEmptyState');
    if (emptyMsg) {
        if (visibleCount === 0) {
            emptyMsg.classList.remove('hidden');
            document.getElementById('filterEmptyLabel').textContent = group === 'all' ? '' : group;
        } else {
            emptyMsg.classList.add('hidden');
        }
    }
}

// Timeline Modal Functions
function openTimelineModal(req) {
    document.getElementById('modalTypeBadge').textContent = req._type;
    document.getElementById('modalTitle').textContent     = req.name || req.owner_name || req._type;

    const createdDate = req.created_at ? new Date(req.created_at).toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' }) : 'N/A';
    document.getElementById('modalSub').textContent       = 'Submitted on ' + createdDate;

    const grid = document.getElementById('modalDetailsGrid');
    grid.innerHTML = '';
    if (req._details_kv) {
        Object.keys(req._details_kv).forEach(key => {
            const val = req._details_kv[key];
            if (val) {
                const item = document.createElement('div');
                item.className = 'flex flex-col';
                item.innerHTML = `<span class="text-gray-400 font-medium">${key}</span><span class="text-gray-800 font-semibold truncate" title="${val}">${val}</span>`;
                grid.appendChild(item);
            }
        });
    }

    const status = req.approval_status || 'pending';
    const rejLvl = req.rejected_by_level || null;
    const rejReason = req.rejection_reason || 'No reason provided.';

    const setCompletedIcon = (el) => {
        el.className = 'w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0 shadow-md';
        el.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
    };
    const setPendingIcon = (el) => {
        el.className = 'w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md pulse-amber';
        el.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    };
    const setInProgressIcon = (el) => {
        el.className = 'w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-md pulse-amber';
        el.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>';
    };
    const setRejectedIcon = (el) => {
        el.className = 'w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0 shadow-md';
        el.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
    };
    const setWaitingIcon = (el, num) => {
        el.className = 'w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center shrink-0 shadow-md';
        el.innerHTML = num;
    };

    document.getElementById('step1Date').textContent = createdDate;
    document.getElementById('step1Desc').textContent = `Submitted by ${req.name || req.owner_name || 'applicant'} (${req.email || req.institute_email || ''})`;

    const approver1Name  = req.approver1_name || req.approver_name || 'Designated Faculty/Staff';
    const approver1Email = req.approver1_email || req.approver_email || '';
    document.getElementById('step2Approver').textContent = `Approver: ${approver1Name} (${approver1Email})`;

    const step2Icon = document.getElementById('step2Icon');
    const step2Badge = document.getElementById('step2Badge');
    const step2Desc = document.getElementById('step2Desc');
    const step2RejBox = document.getElementById('step2RejectionBox');
    step2RejBox.classList.add('hidden');

    if (status === 'pending') {
        setPendingIcon(step2Icon);
        step2Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700';
        step2Badge.textContent = 'Pending Review';
        step2Desc.textContent = 'Awaiting review and approval by designated faculty/staff member.';
    } else if (['approved_by_1', 'approved_by_2', 'completed'].includes(status)) {
        setCompletedIcon(step2Icon);
        step2Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-green-100 text-green-700';
        step2Badge.textContent = 'Approved';
        const dateStr = req.approved_by_1_at ? new Date(req.approved_by_1_at).toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' }) : 'Approved';
        step2Desc.textContent = `Approved on ${dateStr}`;
    } else if (status === 'rejected' && (rejLvl == 1 || !rejLvl)) {
        setRejectedIcon(step2Icon);
        step2Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-100 text-red-700';
        step2Badge.textContent = 'Rejected';
        step2Desc.textContent = 'Rejected by Level 1 approver.';
        step2RejBox.classList.remove('hidden');
        document.getElementById('step2ReasonText').textContent = rejReason;
    } else {
        setWaitingIcon(step2Icon, '2');
        step2Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500';
        step2Badge.textContent = 'Waiting';
    }

    const step3Icon = document.getElementById('step3Icon');
    const step3Badge = document.getElementById('step3Badge');
    const step3Desc = document.getElementById('step3Desc');
    const step3RejBox = document.getElementById('step3RejectionBox');
    step3RejBox.classList.add('hidden');

    if (status === 'approved_by_1') {
        setPendingIcon(step3Icon);
        step3Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700';
        step3Badge.textContent = 'Under Review by Dean';
        step3Desc.textContent = 'Approved by Level 1. Now awaiting review by Dean of IT Infrastructure.';
    } else if (['approved_by_2', 'completed'].includes(status)) {
        setCompletedIcon(step3Icon);
        step3Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-green-100 text-green-700';
        step3Badge.textContent = 'Approved by Dean';
        const dateStr = req.approved_by_2_at ? new Date(req.approved_by_2_at).toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' }) : 'Approved';
        step3Desc.textContent = `Approved by Dean IT on ${dateStr}`;
    } else if (status === 'rejected' && rejLvl == 2) {
        setRejectedIcon(step3Icon);
        step3Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-100 text-red-700';
        step3Badge.textContent = 'Rejected by Dean';
        step3Desc.textContent = 'Rejected by Dean of IT Infrastructure.';
        step3RejBox.classList.remove('hidden');
        document.getElementById('step3ReasonText').textContent = rejReason;
    } else {
        setWaitingIcon(step3Icon, '3');
        step3Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500';
        step3Badge.textContent = 'Waiting';
        step3Desc.textContent = 'Will be sent to Dean IT after Level 1 approval.';
    }

    const step4Icon = document.getElementById('step4Icon');
    const step4Badge = document.getElementById('step4Badge');
    const step4Desc = document.getElementById('step4Desc');

    if (status === 'approved_by_2') {
        setInProgressIcon(step4Icon);
        step4Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700';
        step4Badge.textContent = 'In Progress at CITC';
        step4Desc.textContent = 'Fully approved by Dean. CITC technical team is setting up the service.';
    } else if (status === 'completed') {
        setCompletedIcon(step4Icon);
        step4Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-green-100 text-green-700';
        step4Badge.textContent = 'Service Active';
        const dateStr = req.citc_completed_at ? new Date(req.citc_completed_at).toLocaleString('en-IN', { dateStyle: 'medium', timeStyle: 'short' }) : 'Completed';
        step4Desc.textContent = `Service setup completed by ${req.citc_completed_by || 'CITC Team'} on ${dateStr}`;
    } else if (status === 'rejected' && rejLvl == 3) {
        setRejectedIcon(step4Icon);
        step4Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-red-100 text-red-700';
        step4Badge.textContent = 'Declined by CITC';
        step4Desc.textContent = 'Declined during final execution by CITC team.';
    } else {
        setWaitingIcon(step4Icon, '4');
        step4Badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500';
        step4Badge.textContent = 'Waiting';
        step4Desc.textContent = 'Pending earlier approval steps.';
    }

    document.getElementById('timelineModal').classList.add('open');
}

function closeTimelineModal() {
    document.getElementById('timelineModal').classList.remove('open');
}
</script>

@endsection
