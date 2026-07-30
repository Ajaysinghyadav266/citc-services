@extends('layouts.approver')

@section('title', 'Approved Requests')

@section('content')

@php
    $lvl = session('approver_level', 1);
@endphp

<div class="pt-2">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Approved Requests</h1>
        <p class="text-gray-500 text-sm mt-1">Requests you have approved — now progressing through the workflow</p>
    </div>

    @if(count($requests) === 0)
    <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">No approved requests yet</p>
        <p class="text-gray-400 text-sm mt-1">Requests you approve will appear here.</p>
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Requester</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Service</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Approved At</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Current Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($requests as $r)
                    @php
                        $requesterName  = $r->name ?? $r->owner_name ?? '—';
                        $requesterEmail = $r->email ?? $r->institute_email ?? '—';
                        $approvedAt = $lvl === 1 ? $r->approved_by_1_at : $r->approved_by_2_at;
                        $statusColors = [
                            'approved_by_1'=> 'bg-blue-100 text-blue-700',
                            'approved_by_2'=> 'bg-indigo-100 text-indigo-700',
                            'completed'    => 'bg-green-100 text-green-700',
                        ];
                        $statusLabel = [
                            'approved_by_1'=> 'Awaiting L2',
                            'approved_by_2'=> 'Awaiting CITC',
                            'completed'    => 'Completed',
                        ];
                        $sc = $statusColors[$r->approval_status] ?? 'bg-gray-100 text-gray-600';
                        $sl = $statusLabel[$r->approval_status] ?? ucfirst($r->approval_status);
                        $typeColors = [
                            'VPN'             => 'bg-indigo-100 text-indigo-700',
                            'Internet Access' => 'bg-cyan-100 text-cyan-700',
                            'VM Request'      => 'bg-green-100 text-green-700',
                            'Web Hosting'     => 'bg-orange-100 text-orange-700',
                        ];
                        $tc = $typeColors[$r->_type] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($requesterName, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $requesterName }}</p>
                                    <p class="text-gray-400 text-xs">{{ $requesterEmail }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $tc }}">{{ $r->_type }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $approvedAt?->format('d M Y, h:i A') ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc }}">{{ $sl }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@endsection
