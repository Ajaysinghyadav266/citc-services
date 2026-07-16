<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Submission Success</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -40px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.95); }
    }
    .animate-blob { animation: blob 10s infinite ease-in-out; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }

    @keyframes pop-in {
        0% { transform: scale(0); opacity: 0; }
        70% { transform: scale(1.15); opacity: 1; }
        100% { transform: scale(1); }
    }
    .animate-pop-in { animation: pop-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

    @keyframes fade-up {
        0% { transform: translateY(12px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    .animate-fade-up { animation: fade-up 0.6s ease-out forwards; }
</style>
</head>
<body class="min-h-screen relative flex items-center justify-center overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950">

    <!-- Blurred decorative background blobs (IIT Indore blue / gold theme) -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-1/3 -right-24 w-96 h-96 bg-amber-400 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-24 left-1/3 w-96 h-96 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Top-left institute badge -->
    <div class="absolute top-6 left-6 flex items-center gap-3 z-20">
        <img src="https://www.iiti.ac.in/public/themes/iitindore/demos/update-logo.png"
             alt="IIT Indore Logo"
             class="h-14 w-auto drop-shadow-lg">
        <div class="leading-tight">
            <p class="text-white font-bold text-sm sm:text-base tracking-wide">IIT INDORE</p>
            <p class="text-blue-200 text-[11px] sm:text-xs">Indian Institute of Technology Indore</p>
        </div>
    </div>

    <!-- Main card -->
    <div class="relative z-10 bg-white/95 backdrop-blur-xl p-10 sm:p-12 rounded-3xl shadow-2xl text-center max-w-lg mx-4 border border-white/60">

        <!-- Success icon -->
        <div class="mx-auto mb-5 w-20 h-20 rounded-full bg-green-100 flex items-center justify-center animate-pop-in">
            <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-blue-950 mb-3 animate-fade-up">
            Form Submitted Successfully!
        </h1>

        <p class="text-gray-600 mb-3 animate-fade-up">
            You have successfully submitted the <span class="font-semibold text-blue-900">VPN request form</span> 
        </p>

        <p class="text-gray-600 mb-6 animate-fade-up">
            You should have received the acknowledgment mail. 
            Please check your <span class="font-semibold">Inbox</span> or <span class="font-semibold">Spam folder</span>.
        </p>

        <a href="/dashboard"
           class="inline-flex items-center gap-2 bg-blue-900 text-white px-7 py-2.5 rounded-full font-medium shadow-md hover:bg-blue-800 hover:shadow-lg transition-all duration-200">
            Go to Dashboard
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>
    </div>
</body>
</html>