<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Submitted | IIT Indore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        @keyframes blob {
            0%, 100% { transform: translate(0,0) scale(1); }
            33% { transform: translate(30px,-40px) scale(1.1); }
            66% { transform: translate(-20px,20px) scale(0.95); }
        }
        .animate-blob { animation: blob 12s infinite ease-in-out; }
        .delay-2000 { animation-delay: 2s; }
        @keyframes pop-in {
            0%   { opacity: 0; transform: scale(0.7); }
            80%  { transform: scale(1.05); }
            100% { opacity: 1; transform: scale(1); }
        }
        .pop-in { animation: pop-in .5s cubic-bezier(.34,1.56,.64,1) forwards; }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fade-up .5s ease forwards; }
        .delay-300 { animation-delay: .3s; opacity: 0; }
        .delay-500 { animation-delay: .5s; opacity: 0; }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-950 flex items-center justify-center px-4">

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-green-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-1/3 -right-24 w-96 h-96 bg-blue-400 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob delay-2000"></div>
    </div>

    <div class="relative z-10 text-center max-w-md w-full">

        {{-- Check icon --}}
        <div class="pop-in mx-auto mb-6 w-24 h-24 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center shadow-2xl shadow-green-500/40">
            <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="fade-up delay-300 text-3xl font-extrabold text-white mb-2">Request Submitted!</h1>
        <p class="fade-up delay-500 text-blue-200 text-sm leading-relaxed mb-8">
            Your <span class="text-white font-semibold">Internet Access Request</span> has been submitted successfully.<br>
            A confirmation email has been sent to your institutional address.<br><br>
            Your request is currently <span class="bg-amber-400/20 text-amber-300 font-semibold px-2 py-0.5 rounded-full text-xs">Pending Approval</span>
        </p>

        {{-- Details card --}}
        <div class="fade-up delay-500 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 text-left mb-8 text-sm text-blue-100 space-y-2">
            <p class="text-white font-semibold text-xs uppercase tracking-widest mb-3">What happens next?</p>
            <div class="flex items-start gap-3">
                <span class="text-blue-400 mt-0.5">①</span>
                <span>Your approver receives a notification to review your request.</span>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-blue-400 mt-0.5">②</span>
                <span>CITC team verifies your device details and MAC address.</span>
            </div>
            <div class="flex items-start gap-3">
                <span class="text-blue-400 mt-0.5">③</span>
                <span>You will be notified via email once access is granted.</span>
            </div>
        </div>

        <a href="{{ Auth::check() ? (session('approver_level') ? route('approver.dashboard') : '/dashboard') : '/' }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold px-8 py-3 rounded-xl shadow-lg hover:shadow-blue-500/30 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-9 5v6"/>
            </svg>
            {{ Auth::check() ? 'Back to Dashboard' : 'Back to Home' }}
        </a>
    </div>

</body>
</html>
