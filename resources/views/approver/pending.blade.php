@extends('layouts.approver')

@section('title', 'Pending Requests')

@section('head')
<style>
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; }
    .modal-overlay.open { display:flex; }
</style>
@endsection

@section('content')

@php
    $lvl = session('approver_level', 1);
    $lvlDesc = [
        1 => 'Requests where you are listed as the approver',
        2 => 'Requests approved by Level 1 — awaiting your review',
        3 => 'Requests approved by Level 2 — ready for CITC processing',
    ][$lvl];
    $typeRouteMap = ['VPN' => 'vpn', 'Internet Access' => 'internet', 'VM Request' => 'vm', 'Web Hosting' => 'hosting'];
@endphp

<div class="pt-2">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pending Requests</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $lvlDesc }}</p>
    </div>

    @if(count($requests) === 0)
    <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">No pending requests</p>
        <p class="text-gray-400 text-sm mt-1">You're all caught up!</p>
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Requester</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Service</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Submitted</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($requests as $r)
                    @php
                        $requesterName  = $r->name ?? $r->owner_name ?? '—';
                        $requesterEmail = $r->email ?? $r->institute_email ?? '—';
                        $typeKey        = $typeRouteMap[$r->_type] ?? 'vpn';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($requesterName, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $requesterName }}</p>
                                    <p class="text-gray-400 text-xs">{{ $requesterEmail }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeColors = [
                                    'VPN'             => 'bg-indigo-100 text-indigo-700',
                                    'Internet Access' => 'bg-cyan-100 text-cyan-700',
                                    'VM Request'      => 'bg-green-100 text-green-700',
                                    'Web Hosting'     => 'bg-orange-100 text-orange-700',
                                ];
                                $tc = $typeColors[$r->_type] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $tc }}">{{ $r->_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $r->created_at?->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-orange-100 text-orange-700">Pending</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Approve --}}
                                <form action="{{ route('approver.approve', [$typeKey, $r->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                                {{-- Reject --}}
                                <button type="button"
                                        onclick="openReject('{{ $typeKey }}', {{ $r->id }})"
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Reject
                                </button>
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

{{-- Reject Modal --}}
<div id="reject-modal" class="modal-overlay" onclick="if(event.target===this)closeReject()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-gray-900 text-lg">Reject Request</h3>
            <button onclick="closeReject()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="reject-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="4" required
                          placeholder="Please provide a clear reason so the requester can resubmit if needed..."
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent resize-none"></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeReject()"
                        class="text-sm font-semibold text-gray-600 border border-gray-300 px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="text-sm font-semibold text-white bg-red-600 hover:bg-red-700 px-5 py-2 rounded-xl transition-colors">
                    Confirm Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReject(type, id) {
    document.getElementById('reject-form').action = '/approver/reject/' + type + '/' + id;
    document.getElementById('reject-modal').classList.add('open');
}
function closeReject() {
    document.getElementById('reject-modal').classList.remove('open');
}
</script>

@endsection
