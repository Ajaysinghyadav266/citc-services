@extends('layouts.approver')

@section('title', 'Approver Dashboard')

@section('content')

@php
    $lvl       = session('approver_level', 1);
    $firstName = explode(' ', Auth::user()->name)[0];
    $lvlName   = ['Faculty/Staff Approver', 'Dean of IT Infrastructure', 'CITC Team'][$lvl - 1] ?? 'Approver';
    $accentMap = [
        1 => ['ring'=>'ring-indigo-500','badge'=>'bg-indigo-100 text-indigo-800','icon'=>'text-indigo-600','bg'=>'bg-indigo-50'],
        2 => ['ring'=>'ring-emerald-500','badge'=>'bg-emerald-100 text-emerald-800','icon'=>'text-emerald-600','bg'=>'bg-emerald-50'],
        3 => ['ring'=>'ring-amber-500','badge'=>'bg-amber-100 text-amber-800','icon'=>'text-amber-600','bg'=>'bg-amber-50'],
    ];
    $accent = $accentMap[$lvl];
@endphp

<div class="pt-2">

    {{-- Greeting --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hello, {{ $firstName }} 👋</h1>
            <p class="text-gray-500 mt-1 text-sm">
                You are logged in as <span class="font-semibold text-gray-700">{{ $lvlName }}</span>
                — Level {{ $lvl }} Approver
            </p>
        </div>
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold {{ $accent['badge'] }} self-start">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Level {{ $lvl }} · {{ $lvlName }}
        </span>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ $lvl === 3 ? 'lg:grid-cols-2' : 'lg:grid-cols-3' }} gap-5 mb-8">

        {{-- Pending --}}
        <a href="{{ $lvl === 3 ? route('approver.citc.pending') : route('approver.pending') }}"
           class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-orange-400">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-gray-900">{{ $pending ?? 0 }}</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">Pending Requests</p>
                <p class="text-gray-400 text-xs mt-0.5">Awaiting your action</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-orange-500 text-xs font-semibold">
                Review now
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        @if($lvl !== 3)
        {{-- Approved (L1 & L2) --}}
        <a href="{{ route('approver.approved') }}"
           class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-green-500">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-gray-900">{{ $approved ?? 0 }}</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">Approved Requests</p>
                <p class="text-gray-400 text-xs mt-0.5">Approved at your level</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-green-600 text-xs font-semibold">
                View all
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        {{-- Rejected (L1 & L2) --}}
        <a href="{{ route('approver.rejected') }}"
           class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-red-400">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-gray-900">{{ $rejected ?? 0 }}</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">Rejected Requests</p>
                <p class="text-gray-400 text-xs mt-0.5">Rejected at your level</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-red-500 text-xs font-semibold">
                View all
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @else
        {{-- Completed (L3) --}}
        <a href="{{ route('approver.citc.completed') }}"
           class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-blue-500">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-gray-900">{{ $completed ?? 0 }}</span>
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-sm">Completed Requests</p>
                <p class="text-gray-400 text-xs mt-0.5">Processed by CITC</p>
            </div>
            <div class="mt-auto flex items-center gap-1 text-blue-600 text-xs font-semibold">
                View all
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endif

    </div>

    {{-- Divider + Service Cards (same as user dashboard) --}}
    <div class="mb-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Submit a New Request</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <a href="/vpn-form"
               class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-indigo-500">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h.01M15 12h.01M17 16H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">VPN Access</p>
                    <p class="text-gray-400 text-xs mt-0.5">Request secure VPN connectivity</p>
                </div>
                <div class="mt-auto flex items-center gap-1 text-indigo-600 text-xs font-semibold">
                    Request <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <a href="/internet-access"
               class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-cyan-500">
                <div class="w-12 h-12 rounded-xl bg-cyan-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">Internet Access</p>
                    <p class="text-gray-400 text-xs mt-0.5">Register your device for campus internet</p>
                </div>
                <div class="mt-auto flex items-center gap-1 text-cyan-600 text-xs font-semibold">
                    Request <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <a href="/vm-request-application/new"
               class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-green-500">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">Virtual Machine</p>
                    <p class="text-gray-400 text-xs mt-0.5">Provision a VM in CITC data centre</p>
                </div>
                <div class="mt-auto flex items-center gap-1 text-green-600 text-xs font-semibold">
                    Request <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <a href="/web-host"
               class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col gap-4 border-b-4 border-b-orange-500">
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">Web Hosting</p>
                    <p class="text-gray-400 text-xs mt-0.5">Host your project or department website</p>
                </div>
                <div class="mt-auto flex items-center gap-1 text-orange-600 text-xs font-semibold">
                    Request <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

        </div>
    </div>

    {{-- Recent Activity --}}
    @if(count($recent) > 0)
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900 text-sm">Recent Requests</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($recent as $r)
            <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold shrink-0">
                        {{ strtoupper(substr($r->name ?? $r->owner_name ?? '?', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $r->name ?? $r->owner_name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $r->_type }} · {{ $r->created_at?->diffForHumans() }}</p>
                    </div>
                </div>
                @php
                    $statusColors = [
                        'pending'      => 'bg-orange-100 text-orange-700',
                        'approved_by_1'=> 'bg-blue-100 text-blue-700',
                        'approved_by_2'=> 'bg-indigo-100 text-indigo-700',
                        'completed'    => 'bg-green-100 text-green-700',
                        'rejected'     => 'bg-red-100 text-red-700',
                    ];
                    $statusLabel = [
                        'pending'      => 'Pending',
                        'approved_by_1'=> 'L1 Approved',
                        'approved_by_2'=> 'L2 Approved',
                        'completed'    => 'Completed',
                        'rejected'     => 'Rejected',
                    ];
                    $sc = $statusColors[$r->approval_status ?? 'pending'] ?? 'bg-gray-100 text-gray-600';
                    $sl = $statusLabel[$r->approval_status ?? 'pending'] ?? ucfirst($r->approval_status ?? 'Pending');
                @endphp
                <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc }}">{{ $sl }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-blue-700 text-sm">No requests awaiting your action yet. You'll be notified when new requests require your approval.</p>
    </div>
    @endif

</div>

@endsection
