<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | IIT INDORE</title>
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
    </style>
</head>
<body class="min-h-screen relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-indigo-950 font-sans">

    <!-- Blurred decorative background blobs (IIT Indore blue / gold theme) -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-1/3 -right-24 w-96 h-96 bg-amber-400 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-24 left-1/3 w-96 h-96 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Top-left institute badge -->
    <div class="absolute top-6 left-6 flex items-center gap-3 z-20">
        <img src="https://www.iiti.ac.in/public/themes/iitindore/demos/update-logo.png"
             alt="IIT Indore Logo"
             class="h-14 w-auto drop-shadow-lg">
        <div class="leading-tight">
            <p class="text-white font-bold text-sm sm:text-base tracking-wide">IIT INDORE</p>
            <p class="text-blue-200 text-[11px] sm:text-xs">Indian Institute of Technology</p>
        </div>
    </div>

    <!-- Main content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-xl p-9 sm:p-10 rounded-3xl shadow-2xl border border-white/60 text-center">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-2.5 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- User Info --}}
            <div class="mb-8">
                <div class="mx-auto mb-4 w-16 h-16 rounded-full bg-gradient-to-br from-blue-800 to-blue-950 flex items-center justify-center text-white text-xl font-bold shadow-md">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-blue-950">
                    Welcome, {{ Auth::user()->name }}
                </h2>
                <p class="text-gray-500 text-sm mt-1">{{ Auth::user()->email }}</p>
            </div>

            {{-- VPN Form Button --}}
            <a href="/vpn-form"
               class="flex items-center justify-center gap-2 w-full bg-blue-900 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-blue-800 hover:shadow-lg transition-all duration-200 mb-4">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h.01M15 12h.01M17 16H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2z" />
                </svg>
                Fill VPN Request Form
            </a>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center gap-2 w-full bg-red-600 text-white px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-red-700 hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>

        </div>
    </div>

</body>
</html>