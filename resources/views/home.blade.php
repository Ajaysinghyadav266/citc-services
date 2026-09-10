<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CITC Services | IIT Indore</title>
    <meta name="description" content="IIT Indore Computer & IT Centre — Request VPN, Internet Access, Virtual Machine, and Web Hosting services.">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        * { font-family: 'Inter', sans-serif; }

        body {
            background: linear-gradient(135deg, #e8eef8 0%, #dce6f5 40%, #e4eaf8 70%, #d8e4f5 100%);
            min-height: 100vh;
        }

        /* Soft background blobs like HMS */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            pointer-events: none;
        }

        /* Service card */
        .service-card {
            background: #fff;
            border-radius: 16px;
            padding: 28px 20px 20px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-bottom: 4px solid transparent;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }
        .service-card .icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .service-card .label {
            font-size: 13px;
            font-weight: 600;
            color: #2d3748;
            line-height: 1.4;
        }

        /* Color accents */
        .card-vpn       { border-bottom-color: #4f46e5; }
        .card-vpn       .icon-wrap { background: #ede9fe; }
        .card-internet  { border-bottom-color: #0891b2; }
        .card-internet  .icon-wrap { background: #cffafe; }
        .card-vm        { border-bottom-color: #16a34a; }
        .card-vm        .icon-wrap { background: #dcfce7; }
        .card-hosting   { border-bottom-color: #ea580c; }
        .card-hosting   .icon-wrap { background: #ffedd5; }
        .card-approver  { border-bottom-color: #9333ea; }
        .card-approver  .icon-wrap { background: #f3e8ff; }

        /* Section box */
        .section-box {
            background: rgba(255,255,255,0.65);
            backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 28px;
            border: 1px solid rgba(255,255,255,0.8);
            box-shadow: 0 4px 24px rgba(99,130,200,0.1);
        }
        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 16px;
        }

        /* Login button */
        .login-btn {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 26px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.18s ease, box-shadow 0.18s ease;
            box-shadow: 0 2px 8px rgba(37,99,235,0.3);
        }
        .login-btn:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 16px rgba(37,99,235,0.4);
        }
    </style>
</head>
<body class="relative overflow-x-hidden">

    <!-- Background blobs -->
    <div class="blob w-96 h-96 bg-blue-300" style="top:-80px;left:-100px;"></div>
    <div class="blob w-80 h-80 bg-indigo-200" style="bottom:0;right:-60px;"></div>
    <div class="blob w-64 h-64 bg-blue-200" style="top:40%;left:40%;"></div>

    <!-- NAVBAR -->
    <nav class="relative z-10 flex items-center justify-between px-8 py-4">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="{{ asset('logo.png') }}"
                 alt="IIT Indore Logo" class="h-12 w-auto">
            <div class="leading-tight">
                <p class="text-slate-800 font-bold text-sm tracking-wide">IIT INDORE</p>
                <p class="text-slate-500 text-xs">Computer &amp; IT Centre</p>
            </div>
        </div>

        <!-- Login button -->
        <a href="/login" class="login-btn">Login →</a>
    </nav>

    <!-- HERO + CARDS -->
    <main class="relative z-10 max-w-6xl mx-auto px-6 py-10 lg:py-16">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-12">

            <!-- LEFT: Hero text -->
            <div class="flex-1 text-center lg:text-left pt-4">
                <p class="text-blue-600 font-semibold text-sm mb-3 tracking-wide">Indian Institute of Technology Indore</p>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-slate-800 leading-tight mb-4">
                    Welcome to<br>
                    <span class="text-blue-600">CITC Services</span>
                </h1>
                <p class="text-slate-500 text-base leading-relaxed mb-8 max-w-md mx-auto lg:mx-0">
                    Request and manage all IT infrastructure services — VPN access, Internet access,
                    Virtual Machines, and Web Hosting — in one place.
                </p>
                <a href="/login" class="login-btn inline-block text-center">
                    Login to Dashboard →
                </a>
            </div>

            <!-- RIGHT: Service cards -->
            <div class="flex-1 w-full max-w-lg">

                <!-- Requester Block -->
                <div class="section-box mb-5">
                    <p class="section-label">👤 Requester Services</p>
                    <div class="grid grid-cols-2 gap-4">

                        <!-- VPN -->
                        <a href="/vpn-form" class="service-card card-vpn">
                            <div class="icon-wrap">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 12h.01M15 12h.01M17 16H7a2 2 0 01-2-2v-4a2 2 0 012-2h10a2 2 0 012 2v4a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="label">VPN Access</span>
                        </a>

                        <!-- Internet Access -->
                        <a href="/internet-access" class="service-card card-internet">
                            <div class="icon-wrap">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                </svg>
                            </div>
                            <span class="label">Internet Access</span>
                        </a>

                        <!-- VM -->
                        <a href="/vm-request-application/new" class="service-card card-vm">
                            <div class="icon-wrap">
                                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                                </svg>
                            </div>
                            <span class="label">Virtual Machine</span>
                        </a>

                        <!-- Web Hosting -->
                        <a href="/web-host" class="service-card card-hosting">
                            <div class="icon-wrap">
                                <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                                </svg>
                            </div>
                            <span class="label">Web Hosting</span>
                        </a>

                    </div>
                </div>

                <!-- Approver Block -->
                <div class="section-box">
                    <p class="section-label">🔐 Approver</p>
                    <div class="grid grid-cols-1">

                        <a href="/approver-login" class="service-card card-approver">
                            <div class="icon-wrap">
                                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span class="label">Approver Login</span>
                        </a>

                    </div>
                </div>

            </div><!-- /right -->
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="relative z-10 text-center py-6 text-slate-400 text-xs">
        © {{ date('Y') }} IIT Indore — Computer &amp; IT Centre. All rights reserved.
    </footer>

</body>
</html>
