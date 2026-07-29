@extends('layouts.dashboard')

@section('title', 'Request Submitted')

@section('content')

<div class="max-w-lg mx-auto text-center py-16">

    <div class="mx-auto mb-6 w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">
        <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-2">VPN Request Submitted!</h1>
    <p class="text-gray-500 text-sm mb-8">
        Your VPN request has been submitted successfully.<br>
        A confirmation email has been sent to your institutional address.<br><br>
        Your request is currently <span class="bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full text-xs">Pending Approval</span>
    </p>

    <div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 text-left text-sm text-blue-700 space-y-2 mb-8">
        <p class="font-semibold text-xs uppercase tracking-widest text-blue-500 mb-2">What happens next?</p>
        <div class="flex items-start gap-2"><span>①</span><span>Your approver receives a notification to review your request.</span></div>
        <div class="flex items-start gap-2"><span>②</span><span>CITC team verifies your details.</span></div>
        <div class="flex items-start gap-2"><span>③</span><span>You will be notified via email once access is granted.</span></div>
    </div>

    <a href="{{ session('approver_level') ? route('approver.dashboard') : '/dashboard' }}"
       class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-semibold px-8 py-2.5 rounded-xl text-sm shadow transition-all">
        Back to Dashboard
    </a>
</div>

@endsection