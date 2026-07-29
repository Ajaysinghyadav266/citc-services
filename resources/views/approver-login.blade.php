<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approver Login | CITC Services — IIT Indore</title>
    <meta name="description" content="Approver login portal for CITC Services at IIT Indore. Faculty, Staff, Dean and CITC team can sign in to review and approve requests.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #0f172a 100%);
            min-height: 100vh;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.18;
            pointer-events: none;
        }

        .glass-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
        }

        .google-btn {
            background: #fff;
            color: #1f2937;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            width: 100%;
        }
        .google-btn:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
        }
        .badge-l1 { background: rgba(99,102,241,0.2); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3); }
        .badge-l2 { background: rgba(16,185,129,0.2); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.3); }
        .badge-l3 { background: rgba(245,158,11,0.2); color: #fcd34d; border: 1px solid rgba(245,158,11,0.3); }

        .step-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .back-link {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .back-link:hover { color: rgba(255,255,255,0.85); }

        @keyframes fadeUp {
            from { opacity:0; transform: translateY(20px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .animate-fadeup { animation: fadeUp 0.5s ease forwards; }
        .animate-fadeup-d1 { animation: fadeUp 0.5s 0.1s ease both; }
        .animate-fadeup-d2 { animation: fadeUp 0.5s 0.2s ease both; }
        .animate-fadeup-d3 { animation: fadeUp 0.5s 0.3s ease both; }
    </style>
</head>
<body class="relative overflow-x-hidden min-h-screen flex flex-col">

    <!-- Background blobs -->
    <div class="blob w-[500px] h-[500px] bg-violet-600" style="top:-120px;left:-180px;"></div>
    <div class="blob w-[400px] h-[400px] bg-indigo-600" style="bottom:-80px;right:-120px;"></div>
    <div class="blob w-64 h-64 bg-emerald-500" style="top:40%;left:55%;"></div>

    <!-- Navbar -->
    <nav class="relative z-10 flex items-center justify-between px-6 py-4">
        <a href="/" class="flex items-center gap-3">
            <img src="http://beta.iiti.ac.in/images/logo.png"
                 alt="IIT Indore Logo"
                 class="h-10 w-auto"
                 onerror="this.src='https://www.iiti.ac.in/public/themes/iitindore/demos/update-logo.png'">
            <div class="leading-tight">
                <p class="text-white font-bold text-sm tracking-wide">IIT INDORE</p>
                <p class="text-white/50 text-xs">Computer &amp; IT Centre</p>
            </div>
        </a>
        <a href="/login" class="back-link">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Student Login
        </a>
    </nav>

    <!-- Main content -->
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            <!-- Error Alert -->
            @if(session('error'))
            <div class="animate-fadeup mb-6 bg-red-500/20 border border-red-500/40 rounded-xl px-4 py-3 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-red-300 text-sm font-medium">{{ session('error') }}</p>
            </div>
            @endif

            <!-- Glass Card -->
            <div class="glass-card p-8 animate-fadeup">

                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-xl shadow-violet-900/40">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-1">Approver Login</h1>
                    <p class="text-white/50 text-sm">Sign in with your IITI Google account</p>
                </div>

                <!-- Google Sign In -->
                <div class="animate-fadeup-d1 mb-8">
                    <a href="/approver-login/google" id="approver-google-signin" class="google-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Continue with Google (IITI)
                    </a>
                    <p class="text-center text-white/30 text-xs mt-3">Only @iiti.ac.in accounts are permitted</p>
                </div>

                <!-- Divider -->
                <div class="border-t border-white/10 mb-6"></div>

                <!-- Role Workflow -->
                <div class="animate-fadeup-d2">
                    <p class="text-white/40 text-xs font-semibold uppercase tracking-widest mb-4">Approval Workflow</p>

                    <div class="space-y-4">

                        <div class="flex items-start gap-3">
                            <div class="step-dot bg-indigo-500/30 text-indigo-300 mt-0.5">1</div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <p class="text-white text-sm font-semibold">Faculty / Staff Approver</p>
                                    <span class="role-badge badge-l1">Level 1</span>
                                </div>
                                <p class="text-white/40 text-xs leading-relaxed">Any IIT Indore faculty or staff member listed as approver on a submitted request. Verified via ERP directory.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="step-dot bg-emerald-500/30 text-emerald-300 mt-0.5">2</div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <p class="text-white text-sm font-semibold">Dean of IT Infrastructure</p>
                                    <span class="role-badge badge-l2">Level 2</span>
                                </div>
                                <p class="text-white/40 text-xs leading-relaxed">Logged in as <span class="text-emerald-400 font-medium">doita@iiti.ac.in</span>. Reviews requests after Level 1 approval.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="step-dot bg-amber-500/30 text-amber-300 mt-0.5">3</div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <p class="text-white text-sm font-semibold">CITC Team</p>
                                    <span class="role-badge badge-l3">Level 3</span>
                                </div>
                                <p class="text-white/40 text-xs leading-relaxed">Department: <span class="text-amber-400 font-medium">Computer and Information Technology Center (CITC)</span>. Final request handler.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 text-center py-5 text-white/20 text-xs">
        © {{ date('Y') }} IIT Indore — Computer &amp; IT Centre. All rights reserved.
    </footer>

</body>
</html>
