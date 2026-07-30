@extends('layouts.approver')

@section('title', 'Pending — CITC')

@section('head')
<style>
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; }
    .modal-overlay.open { display:flex; }
</style>
@endsection

@section('content')

@php
    $typeRouteMap = ['VPN' => 'vpn', 'Internet Access' => 'internet', 'VM Request' => 'vm', 'Web Hosting' => 'hosting'];
@endphp

<div class="pt-2">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pending Requests — CITC</h1>
        <p class="text-gray-500 text-sm mt-1">Requests fully approved and awaiting CITC to initiate the service</p>
    </div>

    @if(count($requests) === 0)
    <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">No pending service initiations</p>
        <p class="text-gray-400 text-sm mt-1">All approved requests have been processed.</p>
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Requester</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Service</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">L2 Approved</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Approver 2</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($requests as $r)
                    @php
                        $requesterName  = $r->name ?? $r->owner_name ?? '—';
                        $requesterEmail = $r->email ?? $r->institute_email ?? '—';
                        $typeKey = $typeRouteMap[$r->_type] ?? 'vpn';
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
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 text-xs font-bold shrink-0">
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
                            {{ $r->approved_by_2_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-xs">{{ $r->approver2_name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end">
                                <form action="{{ route('approver.approve', [$typeKey, $r->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 px-4 py-1.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Mark Complete
                                    </button>
                                </form>
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

@endsection
