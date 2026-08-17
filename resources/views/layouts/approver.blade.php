<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Approver Dashboard') | CITC Services — IIT Indore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind:wght@400;500;600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Outfit', sans-serif; }
        .hindi-text { font-family: 'Hind', sans-serif; }

        .nav-link.active {
            background: #1e3a8a;
            color: #fff !important;
        }
        .nav-link {
            transition: background .18s, color .18s;
        }
        .nav-link:hover:not(.active) {
            background: #e2e8f0;
        }

        /* Level-specific accent colours */
        .level-badge-1 { background:#ede9fe; color:#6d28d9; }
        .level-badge-2 { background:#d1fae5; color:#065f46; }
        .level-badge-3 { background:#fef3c7; color:#92400e; }
    </style>
    @yield('head')
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- ====== NAVBAR ====== -->
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-screen-xl mx-auto px-4 py-0 flex items-center justify-between h-[70px]">

            <!-- LEFT: IIT Indore Identity — logo is the home link -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('approver.dashboard') }}">
                    <img src="{{ asset('logo.png') }}"
                         alt="IIT Indore Emblem"
                         class="h-12 w-auto">
                </a>
                <div class="w-px h-10 bg-[#BF7771] mx-1"></div>
                <div class="leading-tight">
                    <p class="hindi-text text-[13px] font-medium text-gray-900 leading-none">भारतीय प्रौद्योगिकी संस्थान इंदौर</p>
                    <p class="text-[11px] font-light text-gray-600 leading-snug mt-0.5">Indian Institute of Technology Indore</p>
                </div>
            </div>

            <!-- CENTRE: Pill navigation — conditional on approver level -->
            <div class="hidden md:flex items-center">
                <div class="flex items-center gap-1 bg-gray-100 rounded-full px-2 py-1.5">

                    <a href="/approver/dashboard"
                       class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('approver/dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    <a href="/approver/pending"
                       class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('approver/pending') ? 'active' : '' }}">
                        Pending Requests
                    </a>

                    @if(session('approver_level') === 3)
                        {{-- Level 3: CITC —— only Pending + Completed --}}
                        <a href="/approver/citc/completed"
                           class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('approver/citc/completed') ? 'active' : '' }}">
                            Completed
                        </a>
                    @else
                        {{-- Level 1 & 2: Approved + Rejected --}}
                        <a href="/approver/approved"
                           class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('approver/approved') ? 'active' : '' }}">
                            Approved Requests
                        </a>

                        <a href="/approver/rejected"
                           class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('approver/rejected') ? 'active' : '' }}">
                            Rejected Requests
                        </a>
                    @endif

                </div>
            </div>

            <!-- RIGHT: Approver info + level badge + logout -->
            <div class="flex items-center gap-3 shrink-0">

                <!-- Approver level badge -->
                @php
                    $lvl = session('approver_level', 1);
                    $lvlClass = ['level-badge-1','level-badge-2','level-badge-3'][$lvl - 1] ?? 'level-badge-1';
                    $lvlLabel = ['L1 · Approver','L2 · Dean IT','L3 · CITC'][$lvl - 1] ?? 'Approver';
                @endphp
                <span class="hidden sm:inline-flex items-center text-[11px] font-bold px-2.5 py-1 rounded-full {{ $lvlClass }}">
                    {{ $lvlLabel }}
                </span>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-violet-900 flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-800 hidden sm:block max-w-[140px] truncate">
                        {{ Auth::user()->name }}
                    </span>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-[12.5px] font-semibold text-gray-600 hover:text-red-600 border border-gray-300 hover:border-red-300 px-3 py-1.5 rounded-full transition-all">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main class="max-w-screen-xl mx-auto px-4 py-8">
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl px-5 py-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-5 py-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
        </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
