@extends('layouts.approver')

@section('title', 'All Requests — CITC Admin')

@section('head')
<style>
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); backdrop-filter: blur(2px);
        z-index: 1000; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .badge-pending       { background:#fef3c7; color:#92400e; }
    .badge-approved_by_1 { background:#dbeafe; color:#1e40af; }
    .badge-approved_by_2 { background:#d1fae5; color:#065f46; }
    .badge-completed     { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
    .badge-rejected      { background:#fee2e2; color:#991b1b; }
    .stage-dot  { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
    .stage-line { flex:1; height:2px; }
    .stage-done   { background:#10b981; }
    .stage-active { background:#f59e0b; }
    .stage-future { background:#e5e7eb; }
</style>
@endsection

@section('content')

@php
    $typeColors = [
        'VPN'             => 'bg-indigo-100 text-indigo-700',
        'Internet Access' => 'bg-cyan-100 text-cyan-700',
        'VM Request'      => 'bg-green-100 text-green-700',
        'Web Hosting'     => 'bg-orange-100 text-orange-700',
    ];
    $statusLabels = [
        'pending'       => 'Pending L1',
        'approved_by_1' => 'Approved L1',
        'approved_by_2' => 'Approved L2',
        'completed'     => 'Completed',
        'rejected'      => 'Rejected',
    ];
    function stageInfo(string $status): array {
        $stages = ['L1 Approval','L2 Approval','CITC Complete'];
        $done = match($status) {
            'pending'       => 0,
            'approved_by_1' => 1,
            'approved_by_2' => 2,
            'completed'     => 3,
            default         => -1,
        };
        return ['stages' => $stages, 'done' => $done];
    }
    $allTypes    = ['VPN','Internet Access','VM Request','Web Hosting'];
    $allStatuses = ['pending','approved_by_1','approved_by_2','completed','rejected'];
@endphp

<div class="pt-2">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">All Requests</h1>
            <p class="text-gray-500 text-sm mt-1">
                Complete view of every submitted request across all services and approval levels.
                <span class="font-semibold text-amber-700">CITC admins can view and delete requests.</span>
            </p>
        </div>
        <span class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-full bg-amber-100 text-amber-800 self-start">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            {{ count($requests) }} Request{{ count($requests) !== 1 ? 's' : '' }}
        </span>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('approver.all-requests') }}"
          class="flex flex-wrap gap-3 mb-6 bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">

        <div class="flex flex-col gap-1 min-w-[160px]">
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Filter by Status</label>
            <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                <option value="">All Statuses</option>
                @foreach($allStatuses as $s)
                    <option value="{{ $s }}" {{ $statusFilter === $s ? 'selected' : '' }}>{{ $statusLabels[$s] ?? $s }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1 min-w-[160px]">
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Filter by Service</label>
            <select name="type" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                <option value="">All Services</option>
                @foreach($allTypes as $t)
                    <option value="{{ $t }}" {{ $typeFilter === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="text-sm font-semibold bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 rounded-lg transition-colors">
                Apply
            </button>
            @if($statusFilter || $typeFilter)
            <a href="{{ route('approver.all-requests') }}" class="text-sm font-semibold text-gray-500 hover:text-red-600 border border-gray-300 hover:border-red-300 px-4 py-2 rounded-lg transition-colors">
                Clear
            </a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    @if(count($requests) === 0)
    <div class="bg-white border border-gray-200 rounded-2xl p-14 text-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">No requests match your filters</p>
        <p class="text-gray-400 text-sm mt-1">Try adjusting the status or service filter above.</p>
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Requester</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Service</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[240px]">Approval Pipeline</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Submitted</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($requests as $r)
                    @php
                        $name   = $r->name ?? $r->owner_name ?? '—';
                        $email  = $r->email ?? $r->institute_email ?? '—';
                        $tc     = $typeColors[$r->_type] ?? 'bg-gray-100 text-gray-600';
                        $status = $r->approval_status ?? 'pending';
                        $badgeClass = 'badge-' . $status;
                        $sl     = $statusLabels[$status] ?? $status;
                        $si     = stageInfo($status);
                        $stages = $si['stages'];
                        $done   = $si['done'];
                    @endphp
                    <tr class="hover:bg-amber-50/40 transition-colors">

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 leading-tight">{{ $name }}</p>
                                    <p class="text-gray-400 text-xs">{{ $email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $tc }}">{{ $r->_type }}</span>
                        </td>

                        <td class="px-5 py-4">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $badgeClass }}">{{ $sl }}</span>
                            @if($status === 'rejected' && $r->rejection_reason)
                            <p class="text-red-400 text-[11px] mt-1 max-w-[140px] truncate" title="{{ $r->rejection_reason }}">"{{ $r->rejection_reason }}"</p>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            @if($status === 'rejected')
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-xs text-red-600 font-semibold">
                                        Rejected at Level {{ $r->rejected_by_level ?? '?' }}
                                        @if($r->rejected_at) · {{ $r->rejected_at->format('d M') }} @endif
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-1">
                                    @foreach($stages as $i => $stage)
                                        @php
                                            $isDone   = $i < $done;
                                            $isActive = ($i === $done && $done < 3);
                                            $dotClass = $isDone ? 'stage-done' : ($isActive ? 'stage-active' : 'stage-future');
                                            $isLast   = ($i === count($stages) - 1);
                                        @endphp
                                        <div class="flex flex-col items-center gap-0.5">
                                            <div class="stage-dot {{ $dotClass }}"></div>
                                            <span class="text-[9px] text-gray-400 whitespace-nowrap">{{ $stage }}</span>
                                        </div>
                                        @if(!$isLast)
                                            <div class="stage-line {{ $isDone ? 'stage-done' : 'stage-future' }} -mt-3 mx-0.5"></div>
                                        @endif
                                    @endforeach
                                    @if($done === 3)
                                        <svg class="w-4 h-4 text-green-600 ml-1 -mt-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex gap-3 mt-1 text-[10px] text-gray-400">
                                    <span>{{ $r->approved_by_1_at?->format('d M') ?? '' }}</span>
                                    <span>{{ $r->approved_by_2_at?->format('d M') ?? '' }}</span>
                                    <span>{{ $r->citc_completed_at?->format('d M') ?? '' }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">
                            {{ $r->created_at->format('d M Y') }}<br>
                            <span class="text-gray-400">{{ $r->created_at->format('H:i') }}</span>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex justify-end items-center gap-2">
                                <button type="button"
                                        onclick="toggleDetail('detail-{{ $r->_typeKey }}-{{ $r->id }}')"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800 border border-blue-200 hover:border-blue-400 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </button>

                                <button type="button"
                                        onclick="confirmDelete('{{ $r->_typeKey }}', {{ $r->id }}, '{{ addslashes($name) }}')"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:text-red-800 border border-red-200 hover:border-red-400 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Expandable Detail Row --}}
                    <tr id="detail-{{ $r->_typeKey }}-{{ $r->id }}" class="hidden bg-amber-50/60 border-t border-amber-100">
                        <td colspan="6" class="px-6 py-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 text-sm">

                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Service Type</p>
                                    <p class="font-medium text-gray-800">{{ $r->_type }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Request ID</p>
                                    <p class="font-medium text-gray-800">#{{ $r->id }}</p>
                                </div>

                                @if(!empty($r->approver_email))
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">L1 Approver (Designated)</p>
                                    <p class="font-medium text-gray-800">{{ $r->approver_name ?? $r->approver_email }}</p>
                                    <p class="text-xs text-gray-400">{{ $r->approver_email }}</p>
                                </div>
                                @endif

                                @if(!empty($r->approver1_email))
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Approved by L1</p>
                                    <p class="font-medium text-gray-800">{{ $r->approver1_name ?? $r->approver1_email }}</p>
                                    <p class="text-xs text-gray-400">{{ $r->approved_by_1_at?->format('d M Y, H:i') }}</p>
                                </div>
                                @endif

                                @if(!empty($r->approver2_email))
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Approved by L2</p>
                                    <p class="font-medium text-gray-800">{{ $r->approver2_name ?? $r->approver2_email }}</p>
                                    <p class="text-xs text-gray-400">{{ $r->approved_by_2_at?->format('d M Y, H:i') }}</p>
                                </div>
                                @endif

                                @if(!empty($r->citc_completed_by))
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Completed by CITC</p>
                                    <p class="font-medium text-gray-800">{{ $r->citc_completed_by }}</p>
                                    <p class="text-xs text-gray-400">{{ $r->citc_completed_at?->format('d M Y, H:i') }}</p>
                                </div>
                                @endif

                                @if($status === 'rejected')
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Rejection Reason</p>
                                    <p class="font-medium text-red-700">{{ $r->rejection_reason ?? '—' }}</p>
                                    <p class="text-xs text-gray-400">By: {{ $r->rejected_by }} · Level {{ $r->rejected_by_level }}</p>
                                </div>
                                @endif

                                <div>
                                    <p class="text-xs text-gray-400 font-semibold uppercase mb-0.5">Submitted</p>
                                    <p class="font-medium text-gray-800">{{ $r->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

{{-- Delete Confirmation Modal --}}
<div class="modal-overlay" id="deleteModal">
    <div class="bg-white rounded-2xl shadow-2xl p-7 max-w-md w-full mx-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900">Delete Request</h3>
                <p class="text-sm text-gray-500">This action cannot be undone.</p>
            </div>
        </div>

        <p class="text-sm text-gray-700 mb-6">
            Are you sure you want to permanently delete the request from
            <span id="deleteRequesterName" class="font-semibold text-gray-900"></span>?
            All associated data will be removed.
        </p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 text-sm font-semibold text-gray-700 border border-gray-300 hover:border-gray-400 px-4 py-2.5 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 px-4 py-2.5 rounded-xl transition-colors">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDetail(id) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('hidden');
}
function confirmDelete(type, id, requesterName) {
    document.getElementById('deleteRequesterName').textContent = requesterName;
    document.getElementById('deleteForm').action = '/approver/delete/' + type + '/' + id;
    document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>

@endsection
