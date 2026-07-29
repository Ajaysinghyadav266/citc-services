<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CITC Services') | IIT Indore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind:wght@400;500;600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Outfit', sans-serif; }
        .hindi-text { font-family: 'Hind', sans-serif; }

        /* Active nav link */
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
    </style>
    @yield('head')
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- ====== NAVBAR ====== -->
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-screen-xl mx-auto px-4 py-0 flex items-center justify-between h-[70px]">

            <!-- LEFT: IIT Indore Identity -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ session('approver_level') ? route('approver.dashboard') : '/dashboard' }}">
                    <img src="http://beta.iiti.ac.in/images/logo.png"
                         alt="IIT Indore Emblem"
                         class="h-12 w-auto"
                         onerror="this.src='https://www.iiti.ac.in/public/themes/iitindore/demos/update-logo.png'">
                </a>
                <div class="w-px h-10 bg-[#BF7771] mx-1"></div>
                <div class="leading-tight">
                    <p class="hindi-text text-[13px] font-medium text-gray-900 leading-none">भारतीय प्रौद्योगिकी संस्थान इंदौर</p>
                    <p class="text-[11px] font-light text-gray-600 leading-snug mt-0.5">Indian Institute of Technology Indore</p>
                </div>
            </div>

            <!-- CENTRE: Pill navigation -->
            <div class="hidden md:flex items-center">
                <div class="flex items-center gap-1 bg-gray-100 rounded-full px-2 py-1.5">

                    <a href="/vpn-form"
                       class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('vpn-form') ? 'active' : '' }}">
                        VPN
                    </a>

                    <a href="/internet-access"
                       class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('internet-access') ? 'active' : '' }}">
                        Internet Access
                    </a>

                    <a href="/vm-request-application/new"
                       class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('vm-request-application*') ? 'active' : '' }}">
                        Virtual Machine
                    </a>

                    <a href="/web-host"
                       class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('web-host') ? 'active' : '' }}">
                        Web Hosting
                    </a>

                    <a href="/my-requests"
                       class="nav-link text-[12.5px] font-semibold text-gray-700 px-4 py-1.5 rounded-full {{ request()->is('my-requests') ? 'active' : '' }}">
                        My Requests
                    </a>

                </div>
            </div>

            <!-- RIGHT: User profile + logout -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center text-white text-xs font-bold shrink-0">
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
        @yield('content')
    </main>

</body>
</html>
