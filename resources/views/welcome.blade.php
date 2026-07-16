<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | IIT Indore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen relative flex items-center justify-center overflow-hidden font-sans bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">

    {{-- Blurred glow orbs for the dark navy ambience --}}
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-600/30 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-40 -right-40 w-[450px] h-[450px] bg-blue-400/20 rounded-full blur-[120px]"></div>

    {{-- IIT Indore Logo top-left --}}
    <div class="fixed top-6 left-6 z-10 flex items-center gap-2.5">
        <img src="https://www.iiti.ac.in/public/themes/iitindore/demos/update-logo.png"
             alt="IIT Indore Logo"
             class="h-11 w-auto drop-shadow-[0_0_6px_rgba(255,255,255,0.15)]">
        <div class="leading-tight">
            <span class="block text-slate-200 text-sm font-semibold tracking-wide">IIT Indore</span>
            <small class="block text-slate-400 text-[11px]">Indian Institute of Technology</small>
        </div>
    </div>

    {{-- Login Card --}}
    <div class="relative z-[1] w-full max-w-sm mx-5 px-9 pt-11 pb-9 text-center
                bg-slate-900/55 border border-slate-400/20 rounded-2xl
                backdrop-blur-xl shadow-[0_20px_60px_rgba(0,0,0,0.45)]">

        <h1 class="text-slate-100 text-2xl font-bold tracking-wide mb-1.5">Welcome</h1>
        <p class="text-slate-400 text-[13.5px] mb-7">Sign in to continue to your account</p>

        {{-- Error Message --}}
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/35 text-red-300 text-[13px] px-3.5 py-2.5 rounded-lg mb-5">
                {{ session('error') }}
            </div>
        @endif

        {{-- Google Login Button --}}
        <a href="{{ url('/login/google') }}"
           class="flex items-center justify-center gap-2.5 w-full px-4 py-3
                  bg-white text-gray-800 text-[14.5px] font-semibold rounded-xl
                  shadow-[0_6px_16px_rgba(0,0,0,0.25)]
                  transition-transform duration-150 hover:-translate-y-0.5 hover:shadow-[0_10px_24px_rgba(0,0,0,0.35)]">
            <svg class="w-[18px] h-[18px]" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.9-2.26 5.36-4.78 7.02l7.73 6c4.51-4.18 7.09-10.36 7.09-17.49z"/>
                <path fill="#FBBC05" d="M10.53 28.59A14.5 14.5 0 0 1 9.5 24c0-1.59.27-3.13.76-4.59l-7.98-6.19A24 24 0 0 0 0 24c0 3.87.92 7.53 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.97 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            Login with Google
        </a>

        <p class="mt-6 text-slate-500 text-[11.5px]">Use your official IIT Indore Google account</p>
    </div>

</body>
</html>