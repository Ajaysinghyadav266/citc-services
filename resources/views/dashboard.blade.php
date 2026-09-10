@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

<div class="pt-4">

    {{-- Greeting --}}
    <div class="mb-10">
        <h1 class="text-2xl font-bold text-gray-900">
            Hello, {{ explode(' ', Auth::user()->name)[0] }} 👋
        </h1>
        <p class="text-gray-500 mt-1 text-sm">Pick a service you would like to request</p>
    </div>

    {{-- Service Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- VPN --}}
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
                Request
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        {{-- Internet Access --}}
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
                Request
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        {{-- Virtual Machine --}}
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
                Request
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        {{-- Web Hosting --}}
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
                Request
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

    </div>

    {{-- Quick info strip --}}
    <div class="mt-10 bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-blue-700 text-sm">
            All requests require approval from your designated approver. You will be notified by email once your request is processed.
            For support, contact <span class="font-semibold">citc@iiti.ac.in</span>.
        </p>
    </div>

</div>

@endsection